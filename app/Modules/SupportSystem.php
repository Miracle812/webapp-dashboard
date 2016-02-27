<?php namespace Modules;

class SupportSystem extends Modules
{
    public $layout;
    protected $options = [];
    public function __construct()
    {
        $this->activateUserSection();
        $this->user = \Auth::user();
        \View::share('sectionName', 'Support system');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('index'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('create'));

        $view = ($ajax = \Request::ajax()) ? 'modal.create' : 'create';
        $view = \View::make($this->regView($view), compact('breadcrumbs'));
        if ($ajax)
            return $view;
        else
            $this->layout->content = $view;
    }

    public function postCreate()
    {
        $user = $this->user;
        $input = \Input::all();
        $rules = [
            'title'=>'required',
            'message'=>'required',
            'category_id'=>'required',
            'user_name'=>'required',
            'user_email'=>'required|email',
        ];

        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $new = new \Model\SupportTickets();
            $new = $new::create($input+['user_id'=>$user->id]);
            if($new->id) {
                $new->ident = $new -> ident();
                $new->update();
                \Services\FilesUploader::updateAfterCreate(['support_tickets', \Auth::user()->id, $user->unit()->id, $new->id]);
                \Services\Notifications::create($new);
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.create_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function getDatatable()
    {
        if(\Auth::user()->hasRole('admin'))
            $tickets = \Model\SupportTickets::all();
        else {
            $tickets = \Model\SupportTickets::where(function($query){
                $query->
                where('user_id', $this->user->id)->
                orWhere(function($query){
                    $catIds = \Model\SupportAssignedCategories::whereUserId($this->user->id)->lists('category_id');
                    $query->whereIn('category_id',$catIds)->whereIn('status',[0,1]);
                });
            })->get();
        }

        if($tickets->count()) {
            foreach ($tickets as $ticket) {
                $repo = new \Repositories\SupportTickets($ticket);
                $options[] = [
                    strtotime($ticket -> updated_at),
                    $ticket -> updated_at(),
                    '<a href="/support-system/display/'.$ticket->id.'"><span class="font-bold">'.$ticket->title.'</span></a>',
                    $ticket->category->name,
                    $ticket->ident,
                    ($ticket->imAuthor() ? 'Owner' : 'Support'),
                    '<div class="text-center">'.$repo->getStatusHtml().'</div>',
                    '<div class="text-center">'.\HTML::ownNumStatus($ticket->replies->count()).'</div>',
                ];
            };
            if($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }

    public function getDisplay($id)
    {
        $ticket = \Model\SupportTickets::find($id);
        $replies = $ticket -> replies;
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('display'));
        $view = \View::make($this->regView('display'), compact('breadcrumbs','replies','ticket'));
        $this->layout = \View::make($this->layout);
        $this->layout->content = $view;
    }

    public function postReply($id)
    {
        $ticket = \Model\SupportTickets::find($id);
        $rules = [
            'message' => 'required',
            'status'  => 'required',
        ];
        $validator = \Validator::make(\Input::all(), $rules);
        if (!$validator -> fails()){
            $user = \Auth::user();
            $reply = \Model\SupportReplies::create([
                'ticket_id'=>$ticket->id,
                'user_id'=>\Auth::user()->id,
                'message'=>\Input::get('message'),
                'user_name'=>$user->fullname(),
            ]);
            $ticket->update(['status'=>\Input::get('status')]);
            \Services\FilesUploader::updateAfterCreate(['support_replies', $user->id, (($unit = $user->unit())?$unit->id:null), $reply->id]);
            \Services\Notifications::create($reply);
            return \Response::json(['type' => 'success', 'msg' => 'Reply has been sent']);
        }
        else
            return \Response::json(['type' => 'error', 'msg' => 'Fail! Reply hasn\'t been sent', 'errors' => $this->ajaxErrors($validator -> messages() -> toArray(), [])]);
    }

    public function getCategories()
    {
        $users = [];
        $service = new \Services\Messages();
        foreach(['visitor','local-manager','area-manager','hq-manager'] as $role){
            $userInstance = \User::where('active',1)->whereHas('roles',function( $query ) use($role){
                $query->where('name',$role);
            });
            $users[] = ['text'=>\Lang::get('common/general.' . $role), 'children'=>$service->prepareRecipients($userInstance->get())];
        }
        $categories = \Model\SupportCategories::all();
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Categories',false));
        $view = \View::make($this->regView('categories'), compact('breadcrumbs','categories','users'));
        $this->layout = \View::make($this->layout);
        $this->layout->content = $view;
    }
    public function postCategoriesMembers($id)
    {
        $category = \Model\SupportCategories::find($id);
        $members = \Input::get('members') ? (json_decode(\Input::get('members'))) : [];
        if(count($members)) {
            $category->members()->sync($members);
        } else {
            $category->members()->detach();
        }
        return \Response::json(['type' => 'success']);

    }

}

