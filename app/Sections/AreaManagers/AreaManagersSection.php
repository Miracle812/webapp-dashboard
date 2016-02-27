<?php namespace Sections\AreaManagers;

use Services\Breadcrumbs;

class AreaManagersSection extends \BaseController {

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
        $menuStructure -> targetType = 'area-manager';
        \View::share('sectionName',       \Lang::get('/common/sections.'.snake_case($rc -> getShortName()).'.title'));
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

    public function getHqUnitsId()
    {
        $user = \Auth::user();
        if($user->hasRole('hq-manager'))
            return $user->headquarter->units()->lists('id');
        elseif($user->hasRole('area-manager'))
            return $user->units->lists('id');
        else
            return [];
    }
}