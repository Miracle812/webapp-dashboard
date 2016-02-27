<?php namespace Sections\HqManagers;

class OnlineUsers extends HqManagersSection {

    protected $user;
    protected $role;
    protected $roles = ['local-manager','visitor'];
    protected $permission;

    public function __construct(\User $user, \Role $role, \Permission $permission)
    {
        parent::__construct();
        $this -> user = $user;
        $this -> role = $role;
        $this -> permission = $permission;
        $this -> headquarter = $user -> currentUser() -> headquarter();
        $this -> breadcrumbs -> addCrumb('online-users', 'Online users');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $users = \User::
        whereHas(
            'units', function($query) {
             $query -> whereIn('unit_id', $this->headquarter->units->lists('id'));
        })->whereHas(
                'roles', function($query){
                $query -> whereIn('name',$this->roles);
        });

        $users = $users -> get();
        $options = [];
        if ($users->count()){
            $repo = \App::make('UserRepository');
            foreach ($users as $user)
            {
                if($user->isOnline()) {
                    $stats = $user->stats;
                    $options[] = [
                        strtotime($user->created_at),
                        $user->fullname(),
                        $user->username,
                        implode(',', $repo->getUserRoles($user)),
                        ((($count = count($repo->getUserUnits($user))) > 1) ? ('Units: ' . $count) : implode(',', $repo->getUserUnits($user))),
                        $stats->count() ? $user->lastStats()->created_at() : "NA",
                        $stats->count() ? $this->avPagesPerVisit($stats) : "NA",
                        $stats->count() ? $this->durationSpentTimePage($stats) . ' minutes' : "NA",
                    ];
                }
            }
        }
        return \Response::json(['aaData' => $options]);
    }

    public function avPagesPerVisit($stats){
       $sum = $logins = 0;
        foreach ($stats as $stat){
            if($stat->action == 'log_in') {
                $logins += 1;
                $sum += ($stat->tracks->count());
            }
        }
        return ($sum>0)?number_format($sum/$logins,0):0;
    }

    public function durationSpentTimePage($stats){
        $sessions=$time=[];
        foreach ($stats as $stat){
            if($stat->action == 'log_in'){
                $start = $stat->created_at;
                $sess = $stat->session_id;
            }
            if($stat->action == 'log_out' && isset($start) && (isset($sess) && ($sess == $stat->session_id))){
                $sessions[]=['start'=>$start,'end'=>$stat->created_at];
                $start = null;
            }
        }
        if(count($sessions)){
            foreach($sessions as $session){
                $time[] = $session['start']->diffInMinutes($session['end']);

            }
        }
        return count($time)?number_format(array_sum($time)/count($time),0):0;
    }
}