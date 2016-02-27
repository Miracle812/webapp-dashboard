<?php namespace Widgets;

class MessagesSystem extends BaseWidget
{
    public $layout;
    protected $options = [];

    protected $recipientsLinks = [
        'hq-manager' => ['local-manager','area-manager','admin'],
        'area-manager' => ['local-manager','hq-manager','admin'],
        'local-manager' => ['area-manager','admin'],
        'admin' => ['local-manager','area-manager','hq-manager'],
    ];

    public function activateUserSection()
    {
        $user = \Auth::user();
        $this->userRole = $role = $user->role()->name;
        if ($role && in_array($role, ['hq-manager', 'area-manager', 'local-manager', 'admin'])) {
            switch ($role) {
                case 'hq-manager':
                    $this->section = new \Sections\HqManagers\HqManagersSection();
                    $this->layout = '_manager.layouts.manager';
                    break;
                case 'area-manager':
                    $this->section = new \Sections\AreaManagers\AreaManagersSection();
                    $this->layout = '_manager.layouts.manager';
                    break;
                case 'local-manager':
                    $this->section = new \Sections\LocalManagers\LocalManagersSection();
                    $this->layout = '_panel.layouts.panel';
                    break;
                case 'admin':
                    $this->section = new \Sections\Admins\AdminsSection();
                    $this->layout = '_admin.layouts.admin';
                    break;
            }
        }
    }

    public function __construct()
    {
        $this->activateUserSection();
        \View::share('sectionName', 'Messages system');
        $this->section->breadcrumbs->addCrumb('messages-system', 'Messages system');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Threads list', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getCreate()
    {
        $recipients = \Services\Messages::getRecipients($this->recipientsLinks[$this->userRole]);
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('create'));

        $view = ($ajax = \Request::ajax()) ? 'modal.createUI' : 'create';
        $view = \View::make($this->regView($view), compact('recipients'));
        if ($ajax)
            return $view;
        else
            $this->layout->content = $view;
    }

    public function postCreate()
    {
        $create = \Services\Messages::createThread(\Input::all());
        if ($create['status'] == 'success')
        {
            if (\Request::ajax())
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.update_success')]);
            else
                return \Redirect::back()->withInput()->withErrors($create);
        }
        else
        {
            if (\Request::ajax())
                return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($create['errors'], [])]);
            else
                return \Redirect::back()->withInput()->withErrors($create['errors']);
        }
    }
    public function checkCounterMessages($id=null,$type='grouped')
    {
        if($type == 'dialog') {
            $message  = \Model\Messages::find($id);
            $messages = $message->allMessages();
            $mids = $messages->lists('id');
            $parent = $message->parent ? $message->parent : $message;
            $combianation = $parent->id . '_' . array_sum($mids) . '_' . count($mids);
            if ($sess = \Session::get('messages_' . $type . '_counter')) {
                \Session::put('messages_' . $type . '_counter', $combianation);
                return ($sess == $combianation) ? false : $messages;
            } else {
                \Session::put('messages_' . $type . '_counter', $combianation);
                return false;
            }
        }else{
            $messages = \Services\Messages::getGroupedMessages();
            $mids = $messages -> lists('id');
            $combianation =  'grouped_' . array_sum($mids) . '_' . count($mids);

            if ($sess = \Session::get('messages_' . $type . '_counter')) {
                \Session::put('messages_' . $type . '_counter', $combianation);
                return ($sess === $combianation) ? false : $messages;
            } else {
                \Session::put('messages_' . $type . '_counter', $combianation);
                return false;
            }
        }
    }

    public function getDisplay($id)
    {
        $message = \Model\Messages::find($id);
        $messages = $message -> allMessages();
        $recipients = $message -> recipients;
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('display'));
        $view = ($ajax = \Request::ajax()) ? 'modal.display' : 'display';
        $view = \View::make($this->regView($view), compact('breadcrumbs', 'recipients','messages','message'));
        if ($ajax)
            return $view;
        else
            $this->layout->content = $view;
    }

    public function getMarkRead($id)
    {
        return \Model\MessagesRecipients::where('message_id',$id)->whereUserId(\Auth::user()->id)->update(['status'=>1]);
    }

    public function getLiveUpdateGroupedCounter()
    {
        return ($messages = $this->checkCounterMessages()) ? $messages -> count() : '';
    }

    public function getLiveUpdateGroupedMessages()
    {
        return \HTML::NavichatGroupedMessages();
    }

    public function getDialogUpdate($id)
    {
        $html = '';
        if(($messages = $this->checkCounterMessages($id,'dialog')) && $messages->count()) {
            foreach ($messages as $msg) {
                $html .= \HTML::showNaviDialog($msg);
            }
        }
        return $html;
    }

    public function postReply($id)
    {
        $message = \Model\Messages::find($id);
        $rules = [
            'message'     => 'required',
        ];
        $validator = \Validator::make(\Input::all(), $rules);
        if (!$validator -> fails()){
            $newMsg = \Model\Messages::create(['thread_id'=>($message -> thread_id?:$message->id),'author_id'=>\Auth::user()->id,'message'=>\Input::get('message')]);
            $newMsg -> recipients() -> sync ($message -> recipients -> lists('id'));
            return \Response::json(['type' => 'success', 'msg' => 'Message has been sent']);
        }
        else
            return \Response::json(['type' => 'error', 'msg' => 'Fail! Message hasn\'t been sent', 'errors' => $this->ajaxErrors($validator -> messages() -> toArray(), [])]);
    }

    public function getAuthorPlace($author){
        if($author->hasRole('local-manager'))
            return $author->unit()->name;
        elseif($author->hasRole('hq-manager') || $author->hasRole('area-manager'))
            return $author->headquarter()->name;
        else
            return \Lang::get('common/general.not_applicable');
    }

    public function getAddRecipients($idMsg,$list = false)
    {
        $recipients = \Services\Messages::getRecipients($this->recipientsLinks[$this->userRole]);
        $message = \Model\Messages::find($idMsg);
        $messageRecipients = $message->recipients->lists('id');
        $idList = [];
        foreach($recipients as $key1=>$item){
            if(isset($item['children'])){
                foreach($item['children'] as $key2=>$child){
                    if(in_array($child['id'],$messageRecipients)){
                       unset($recipients[$key1][$key2]);
                    }
                    else {
                        $idList[] = $child['id'];
                    }
                }
            }
        }
        return $list ? $idList : \View::make('_default.partials.navichat.add-recipients', compact('message', 'recipients'));
    }

    public function postAddRecipients($id)
    {
        $message = \Model\Messages::find($id);
        $allMessagesId = $message->allMessages()->lists('id');
        $rules = [
            'recipients' => 'required',
        ];
        $inputRecipients = is_array(\Input::get('recipients')) ? \Input::get('recipients') : [];
        $currIds = $this->getAddRecipients($message->id, true);
        if(!array_intersect($inputRecipients, $currIds)) {
            \Input::merge(['recipients'=>'']);
        }
        $validator = \Validator::make(\Input::all(), $rules);
        if (!$validator -> fails()){
            foreach(\Input::get('recipients') as $newId){
                foreach($allMessagesId as $msg){
                    \Model\MessagesRecipients::create(['message_id'=>$msg, 'user_id'=>$newId]);
                }
            }
            return \Response::json(['type' => 'success', 'msg' => 'New recipient(s) has been added.']);
        }
        else
            return \Response::json(['type' => 'error', 'msg' => 'Fail! New recipient(s) hasn\'t been added', 'errors' => $this->ajaxErrors($validator -> messages() -> toArray(), [])]);
    }

    public function getDatatable()
    {
        $user = \Auth::user();
        $msgsIds = [];
        $parentMessages = $user -> messages -> filter(function($item){
            return ($item -> thread_id == 0) ? true : false;
        });
        foreach ($parentMessages as $parent){
            if($parent->childs->count()){
                $childsIds = $parent->childs()->whereRaw('id IN (SELECT max(id) FROM messages GROUP BY thread_id)')->lists('id');
                if(!count($childsIds)){
                    $childsIds = [$parent->id];
                }
                $msgsIds = array_merge($msgsIds, $childsIds);
            }
            else{
                $msgsIds = array_merge($msgsIds, [$parent->id]);
            }
        }
        $messages = \Model\Messages::whereIn('id',$msgsIds)->orderBy('id','desc')->get();

        if($messages->count()) {
            foreach ($messages as $message) {
                $recipients = $message -> recipients;
                $recipientsx = [];
                foreach ($recipients as $key => $recipient){
                    $recipientsx[]=$recipient->fullname();
                }
                $parent =  $message->parent ? $message->parent : $message;
                $options[] = [
                    strtotime($message->updated_at),
                    $message -> updated_at(),
                    implode('<br>',$recipientsx),
                    '<a data-toggle="ajaxModal" class="font-bold" href="'.\URL::to('/messages-system/display/'.$parent->id).'">'.(strip_tags($parent -> message)?:'(HTML tag or iframe)').'</a>',
                    \HTML::ownOuterBuilder(\HTML::ownButton($parent->id, 'messages-system', 'download', 'fa-file'))
                ];
            };

            if ($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }
    public function getDownload($id)
    {
        $message = \Model\Messages::find($id);
        $messages = $message -> allMessages();
        $view = \View::make($this->regView('download'), compact('messages'))->render();
        $view = $this->getLinksIFrames($view);
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($view);
        $pdf -> setOrientation('landscape')
            -> setOption('no-background', true);
        $pdf -> output();
        return $pdf->download('chat-download.pdf');
    }

    function getLinksIFrames($content)
    {
        $regex_pattern_iframe = '/<iframe.+?src="http:\/\/docs.google.com\/gview\?url=(.+?)".+?<\/iframe>/';
        preg_match_all($regex_pattern_iframe,$content,$matches1);
        if($matches1) {
            $content = preg_replace($regex_pattern_iframe, '<a href="$1">$1</a>', $content);
        }
        $regex_pattern_iframe = '/<iframe.+?src="(.+?)".+?<\/iframe>/';
        preg_match_all($regex_pattern_iframe,$content,$matches);
        if($matches) {
            $content = preg_replace($regex_pattern_iframe, '<a href="$1">$1</a>', $content);
        }
        return $content;
    }
}

