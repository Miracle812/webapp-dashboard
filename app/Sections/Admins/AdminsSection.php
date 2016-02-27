<?php namespace Sections\Admins;

use Services\Breadcrumbs;

class AdminsSection extends \BaseController
{
    public $breadcrumbs;
    public function __construct()
    {
        parent::__construct();
        $this->auth_user = \Auth::user();
        $this->breadcrumbs = new Breadcrumbs();
        $rc = new \ReflectionClass (get_called_class());
        $menuStructure = new \Model\MenuStructures();
        $menuStructure->targetType = 'admin';
        \View::share('sectionName', \Lang::get('/common/sections.' . snake_case($rc->getShortName(), ' ') . '.title'));
        \View::share('menuStructure', $menuStructure->getTreeFromDB());
        \View::share('currentUser', $this->auth_user);
    }
}