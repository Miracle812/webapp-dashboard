<?php namespace Sections\HqManagers;

use Services\Breadcrumbs;

class HqManagersSection extends \BaseController {

    public $area = '/';
    public $breadcrumbs;

    public function __construct()
    {
        parent::__construct();
        $user = $this -> auth_user = \Auth::user();
        $this -> headquarter = $user -> headquarter();
        $this -> breadcrumbs = new Breadcrumbs();
        $rc = new \ReflectionClass (get_called_class());

        $menuStructure = new \Model\MenuStructures();
        $menuStructure -> targetType = 'hq-manager';
        \View::share('sectionName',       \Lang::get('/common/sections.'.snake_case($rc -> getShortName(),' ').'.title'));
        \View::share('menuStructure',     $menuStructure -> getTreeFromDB());
        \View::share('section',           $this);
        \View::share('headquarter',       $user -> headquarter());
        \View::share('allUnits',          \Model\Units::whereIn('id',$this->getHqUnitsId())->get());
        \View::share('currentUser',       $user);
        \View::share('currentUnit',       $this -> getCurrentUnit());
    }

    public function getCurrentUnit(){
        $unitId =  \Session::has('unit_id') ? \Session::get('unit_id') : null;
        if(!$unitId) return false;
        if(!$this->isCorrectUnit($unitId)) return false;
        return \Model\Units::find($unitId);
    }

    public function isCorrectUnit($id){
        return in_array($id,$this->getHqUnitsId()) ? true : false;
    }

    public function getUnitsId(){
        $this -> unitsId =  \Session::has('unit_id') ? [\Session::get('unit_id')] : $this -> getHqUnitsId();
        return $this -> unitsId;
    }

    public function getHqUnitsId(){
        return \Auth::user()->headquarter()->getUnitsId();
    }

    public  function redirectIfNotExist(){
        return \Redirect::back() -> withErrors([\Lang::get('/common/messages.not_exist')]);
    }

}