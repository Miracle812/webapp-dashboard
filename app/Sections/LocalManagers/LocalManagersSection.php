<?php namespace Sections\LocalManagers;

use Services\Breadcrumbs;

class LocalManagersSection extends \BaseController {

    public $area    = '/';
    public $breadcrumbs;

    public function __construct()
    {
        parent::__construct();
        $this -> auth_user = \Auth::user();
        $this -> breadcrumbs = new Breadcrumbs();
        $rc = new \ReflectionClass (get_called_class());
        $menuStructure = new \Model\MenuStructures();
        $menuStructure -> targetType = 'local-manager';
        \View::share('sectionName',     \Lang::get('/common/sections.'.snake_case($rc -> getShortName(),' ').'.title'));
        \View::share('menuStructure',   $menuStructure -> getTreeFromDB());
        \View::share('notifications',   null);
        \View::share('currentUser',     $this -> auth_user);
        \View::share('currentUnit',     $this -> auth_user -> unit());
    }

    public function getNotifications()
    {
        /*
        $notifications = \Model\Notifications::where('unit_id','=',$this->auth_user->unit()->id)
            ->where('status','=',0)->where('hidden','=',0)
            ->groupBy('target_type')
            ->groupBy('target_id')
            ->orderBy('created_at','DESC')
            ->get();
        if(!$notifications)
            return null;
        return \Services\Notifications::createHeaderContent($notifications);
        */
    }


    public  function redirectIfNotExist(){
        return \Redirect::to('/') -> withErrors([\Lang::get('/common/messages.not_exist')]);
    }

    public function dateJSConverter($string){
        $date = explode(' ',$string);
        $date = $date[2].' '.$date[1].' '.$date[3].' '.$date[4];
        return \Carbon::createFromTimeStamp(strtotime($date));
    }
}