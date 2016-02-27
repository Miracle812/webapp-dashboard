<?php namespace Sections\HqManagers;
use \Carbon\Carbon;
class UseNavitas extends HqManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('usenavitas', 'Use Navitas');
    }

    public function getIndex()
    {
        $roles = ['local-manager','visitor'];
        $users = \User::
            whereHas(
                'units', function($query) {
                $query-> whereIn('unit_id', $this -> getUnitsId());
            })
            -> whereHas(
                'roles', function($query) use($roles) {
                $query -> whereIn('name', $roles);
            });
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('index') );
        return \View::make($this->regView('index'),
            compact('users','breadcrumbs'));
    }

    public function getLogAs($id)
    {
        $currentUser = \Auth::user();
        $zombiUser = \User::find($id);
        $currUnits = $this->headquarter->units->lists('id');
        if($currentUser && $zombiUser) {
            if(in_array($zombiUser->unit()->id, $currUnits))
            {
                \DB::table('sessions')->whereId(\Session::getId())->delete();
                \Auth::logout();
                \Auth::login($zombiUser);
                \Session::put('zombie_user_id', $currentUser->id);
                return \Redirect::to('/');
            }
        }
        return \Redirect::back();
    }
}    	