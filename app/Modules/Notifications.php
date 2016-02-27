<?php namespace Modules;

class Notifications extends Modules
{
    public $layout;
    protected $options = [];

    public function __construct()
    {
        $this->activateUserSection();
        $this->user = \Auth::user();
        \View::share('sectionName', 'Support system');
        $this->section->breadcrumbs->addCrumb('support-system', 'Support system');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Notifications', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        function getName ($notifi) {
            switch ($notifi->target_type) {
                case'support_tickets':
                    $message = '<a href="/support-system/display/' . $notifi->target_id . '">' . $notifi->message . '</a>'; break;
                case'support_replies':
                    $message = '<a href="/support-system/display/' . $notifi->target()->ticket->id . '">' . $notifi->message . '</a>'; break;
                default:
                    $message = $notifi->message; break;
            }
            return $message;
        }

        $notifications = \Model\Notifications::whereRaw('find_in_set(?, `receivers_id`)', [$this->user->id])->whereHas('userLog',function($q){$q->where('removed',1)->orWhere('read',1);},'=',0)->get();
        if($notifications->count()) {
            foreach ($notifications as $notifi){
                $options[] = [
                    strtotime($notifi -> created_at),
                    $notifi -> created_at(),
                    $notifi->target_type,
                    getName($notifi),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton('remove','notifications',$notifi -> id,'fa-check','btn-success  ajaxAction')
                    ),
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

    public function getRemove($id)
    {
        $notifi = \Model\Notifications::whereRaw('find_in_set(?, `receivers_id`)', [$this->user->id])->find($id);
        $log = \Model\NotificationsLogs::firstOrCreate(['user_id'=>$this->user->id, 'notification_id'=>$notifi->id]);
        $log -> update (['removed'=>1]);
        return \Response::json(['type'=>'success']);

    }
}

