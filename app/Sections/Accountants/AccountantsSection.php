<?php
namespace Sections\Accountants {

    use Services\Breadcrumbs;

    class AccountantsSection extends \BaseController
    {
        public $layout = 'newlayout.base';
        public $breadcrumbs;

        public function __construct()
        {
            parent::__construct();
            $this->layout = \View::make($this->layout);
            $this->breadcrumbs = new Breadcrumbs();
            $rc = new \ReflectionClass (get_called_class());
            \View::share('sectionName', \Lang::get('/common/sections.' . snake_case($rc->getShortName(), ' ') . '.title'));
        }
    }
}
