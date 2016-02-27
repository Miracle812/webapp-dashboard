<?php namespace Sections\LocalManagers;

class Messages extends LocalManagersSection {

    public function __construct(\Model\Messages $item)
    {
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('messages', 'Messages');
    }


    public function getCreate()
    {
        $recipients = \Services\Messages::getAllRecipients();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make( $this -> regView('create'), compact('breadcrumbs','recipients') );
    }

    public function postCreate()
    {
        $create = \Services\Messages::createThread(\Input::all());
        if($create)
            return \Redirect::to('/messages')->with('success', \Lang::get('/common/messages.create_success'));
        else
        {
            return \Redirect::back()->withInput()->withErrors($create);
        }
    }

    public function getDatatable()
    {
        $user = $this->auth_user;
        $threadsIds = \Model\MessagesRecipients::whereRecipientType('users')->whereRecipientId($user->id)->lists('message_id');
        $messasges = \Model\Messages::
            whereThreadId(0)->
            where(function ($query) use($user) {
                $query->whereAuthorType('users')
                      ->whereAuthorId($user->id);
            })->orWhere(function ($query)use($threadsIds) {
                if($threadsIds)
                    $query->whereIn('id',$threadsIds);

            })->orderBy('updated_at','DESC')->get();

        if($messasges->count()) {
            foreach ($messasges as $message) {
                $author = $message->author;

                if($author->hasRole('local-manager'))
                    $target = $author->unit()->name;
                elseif($author->hasRole('hq-manager'))
                    $target = $author->headquarter()->name;
                else
                    $target = \Lang::get('common/general.not_applicable');

                $recipients = $message -> recipients;
                $popoverRecipients = \Services\Messages::prepareRecipients($recipients,true);

                $options[] = [
                    strtotime($message->updated_at),
                    $message -> updated_at(),
                    '<a class="font-bold" href="'.\URL::to('/messages/thread/'.$message->id).'">'.$message -> title.'</a>',
                    '<span class="font-bold" data-placement="top" data-toggle="tooltip" data-original-title="'.\Lang::get('common/general.roles.'.$author -> role() -> name) .' - '. $target.'">'.$author -> fullname().'</span></a>',
                    \HTML::mdOwnPopoverButton(implode('<br>',$popoverRecipients), \HTML::mdOwnOuterBuilder(\HTML::mdOwnNumStatus($recipients->count())),'Recipients'),
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

    public function getThread(\Model\Messages $thread)
    {
        $recipients = $thread->recipients;
        $messages = $thread -> threadMessages();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('thread') );
        return \View::make( $this -> regView('thread'), compact('breadcrumbs','messages','thread','recipients') );
    }

    public function postThread(\Model\Messages $thread)
    {
        $rules = $thread->rules['thread'];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if (!$validator -> fails()) {
            $user = $this -> auth_user;
            $new = new \Model\Messages();
            $new -> fill($input);
            $new -> thread_id = $thread -> id;
            $new -> author_id = $user -> id;
            $new -> author_type =  $user -> getTable();
            $save = $new -> save();
            $send = \Services\Messages::sendMessages($thread);
            $type = $save ? 'success' : 'error';
            $msg  = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');
            return \Redirect::to('/messages')->with($type, $msg);
        } else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }
}