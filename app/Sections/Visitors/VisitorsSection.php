<?php namespace Sections\Visitors;
use Services\Breadcrumbs;
class VisitorsSection extends \BaseController {

    public $area = 'visitor';

    public function __construct()
    {
        parent::__construct();
        $user =  \Auth::user();
        $menuStructure = new \Model\MenuStructures();
        $menuStructure -> targetType = 'visitor';
        $this -> breadcrumbs = new Breadcrumbs();
        $rc = new \ReflectionClass (get_called_class());
        \View::share('sectionName',     \Lang::get('/common/sections.'.snake_case($rc -> getShortName(),' ').'.title'));
        \View::share('menuStructure',   $menuStructure -> getTreeFromDB());
        \View::share('currentUser',     $user);
        \View::share('currentUnit',     $user->unit());
        \View::share('headquarter',     $user->headquarter());
    }

}