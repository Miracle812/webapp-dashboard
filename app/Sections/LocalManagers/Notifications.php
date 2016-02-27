<?php namespace Sections\LocalManagers;

class Notifications extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('notifications', 'Notifications');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        $options=[];
        $user = $this -> auth_user;
        $notifications = \Model\Notifications::whereRaw('find_in_set(?, `receivers_id`)', [$user->id])->orderBy('created_at','DESC')->get();
        if ($notifications -> count()){
            foreach ($notifications as $row)
            {
                $options[] = [
                    strtotime($row->created_at),
                    $row->created_at(),
                    $row->message
                ];
            }
            return \Response::json(['aaData' => $options]);
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getAccept($id)
    {
        $notification = \Model\Notifications::find($id);
        if(!$notification || !$notification -> checkAccess())
            return $this -> redirectIfNotExist();
        $notification -> status = ($notification -> status ? 0 : 1);
        $update = $notification -> update();

        if($update)
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        else
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.update_fail')]);
    }

    public function getDelete($id)
    {
        $notification = \Model\Notifications::find($id);
        if(!$notification || !$notification -> checkAccess())
            return $this -> redirectIfNotExist();

        $notification -> hide = 1;
        $delete = $notification -> update();

        $type = $delete ? 'success' : 'fail';
        $msg  = \Lang::get('/common/messages.delete_'.$type);

        return \Redirect::back()->with($type, $msg);
    }
}