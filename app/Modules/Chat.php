<?php namespace Modules;
use Carbon\Carbon;

class Chat extends Modules
{
    protected $options = [];
    protected $recipientsLinks = [
        'hq-manager' => ['local-manager','area-manager','admin'],
        'area-manager' => ['local-manager','hq-manager','admin'],
        'local-manager' => ['area-manager','admin'],
        'admin' => ['local-manager','area-manager','hq-manager'],
    ];
    public function __construct()
    {
        parent::__construct();
        $this -> activateUserSection();
        $this -> user = \Auth::user();
        \View::share('sectionName', 'Chat');
    }

    public function getIndex($thread = null)
    {
        $recipients = \Services\Messages::getRecipients($this->recipientsLinks[\Auth::user()->role()->name]);
        $this->layout = \View::make($this->layout);
        $this->layout->content = \View::make($this->regView('index'),compact('thread','recipients'));
    }

    public function postMessage()
    {
        $user = \Auth::user();
        $this->firebase->update(\App::environment().'/user/'.$user->id, [
            'user_id'   => $user->id,
            'full_name' => $user->fullname(),
            'role_id'   => $user->role()->id,
            'role_name '=> $user->role()->name,
            'avatar'    => $user->avatar(),
            'online'    => ($user->isOnline() ? 1 : 0)
        ]);

        $rules = ['message'=>'required'];

        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $threadId = (\Input::get('thread_id') ?  : null);

            if(!$threadId){
                $thread_data = [
                    'user_id'    => \Auth::user()->id,
                    'members'    => [['member_id'=>99],['member_id'=>\Auth::user()->id]],
                    'created_at' => \Carbon::now()->toDatetimeString()
                ];
                $thread = $this->firebase->push(\App::environment().'/chat/thread', $thread_data);
                $threadId = $thread['name'];
            }
            $message_data = [
                'thread_id'  => $threadId,
                'user_id'   => \Auth::user()->id,
                'message'   => \Input::get('message'),
                'created_at'=> \Carbon::now()->toDatetimeString()
            ];
            $firebase = $this->firebase->push(\App::environment().'/chat/message', $message_data);
            $data = ['tidx'=>$threadId,'midx' => $firebase['name']];
            return \Response::json(['type'=>'success','data'=>$data]);
        }
        else
        {
            return \Response::json(['type'=>'danger', 'form_errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function createLog($thread, $message)
    {
        $data = [
            'message_id'=> $message->id,
            'user_id'   => $message->author_id,
            'message'   => $message->message,
            'created_at'=> $message->created_at,
        ];

        $this->firebase->push(\App::environment().'/chat/thread/'.$thread->id.'/messages', $data);


    }
}