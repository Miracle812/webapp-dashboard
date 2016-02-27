<?php namespace Modules;

class Modules extends \BaseController
{
    public function activateUserSection()
    {
        $user = \Auth::user();
        $this->userRole = $role = $user->role()->name;
        if ($role && in_array($role, ['hq-manager', 'area-manager', 'local-manager', 'admin', 'client-relation-officer','new-local-manager'])) {
            switch ($role) {
                case 'new-local-manager':
                case 'local-manager':
                case 'client-relation-officer':
                    $this->layout = 'newlayout.base';
                    break;
                case 'hq-manager':
                    $this->section = new \Sections\HqManagers\HqManagersSection();
                    $this->layout = '_manager.layouts.manager';
                    break;
                case 'area-manager':
                    $this->section = new \Sections\AreaManagers\AreaManagersSection();
                    $this->layout = '_manager.layouts.manager';
                    break;
                /*
                case 'local-manager':
                    $this->section = new \Sections\LocalManagers\LocalManagersSection();
                    $this->layout = '_panel.layouts.panel';
                    break;
                */
                case 'admin':
                    $this->section = new \Sections\Admins\AdminsSection();
                    $this->layout = '_admin.layouts.admin';
                    break;
            }
        }
    }
}