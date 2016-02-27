<?php
if(\Auth::check()) {
    $user = \Auth::user();
    if($user->hasRole('local-manager')){
        $home = '/';
        $role = \Role::with(['perms'=>function($query){$query->whereDisabled(0);}])->find($user->role()->id);
        $unit = $user->unit();
        if($unit) {
            $disabledModules = $unit->getEndDisabledModules();
            foreach ($role -> perms as $perm){
                $permission = (  !in_array($perm->id,$disabledModules) ) ? $perm->name : 'sorry-access-not-granted';
                $fMsg = 'Sorry, You don\'t have access to: ' . ucfirst($perm->display_name) .'.';
                $route = $perm->route.'*';
                Entrust::routeNeedsPermission($route, [$permission],
                    Request::ajax() ?
                        Response::make(['aaData' => [], 'type' => 'error', 'msg' => $fMsg]) :
                        Redirect::to($home)
                );
            }
        }
    }
}