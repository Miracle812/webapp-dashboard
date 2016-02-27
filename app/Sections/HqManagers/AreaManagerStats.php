<?php namespace Sections\HqManagers;

class AreaManagerStats extends HqManagersSection {

    public $section = 'usagestats';
    public $hqId;

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('usagestats', 'UsageStats');
    }

    public function getIndex()
    {
        $loggons = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-week';
        $summary = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-month';

        $dateFrom = ['loggons'=>$loggons, 'summary'=>$summary];
        $stats = json_encode($this->getUsersStatistics());
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','stats','dateFrom'));
    }

    public function getDatatable()
    {
        $loggons = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-week';

        if(!\Request::ajax())
        {            
            return $this->redirectIfNotExist();
        }
        $rolesArr = ['area-manager'];
        $sStartDate =  \Services\ChartsTools::getDateFrom($loggons);
        $users = \User::
            whereHas(
            'units', function($query) {
                $query-> whereIn('unit_id', $this -> getUnitsId());
            })
            -> whereHas(
                'roles', function($query) use($rolesArr) {
                $query -> whereIn('name', $rolesArr);
            })

            -> with(['stats'=>function($query)  use($sStartDate)
            {
                $query->whereAction('log_in')-> where('created_at','>=',$sStartDate->format('Y-m-d 00:00:00'))
                    ->with('tracks');
            }])
            -> get();

        $options = [];
        if ($users->count()){
            foreach ($users as $user)
            {
                $statsCount = $user->stats->count();
                $tracksCount = 0;
                if($statsCount){
                    foreach($user->stats as $stat){
                        if($stat->tracks){
                            $tracksCount += $stat->tracks->count();
                        }
                    }
                }

                $average = ($tracksCount && $statsCount) ? $tracksCount / $statsCount : 0;

                $options[] = [
                    '',
                    $user->fullname(),
                    $user->unit()->name,
                    \Lang::get('common/roles.'.$user->role()->name),
                    \HTML::ownOuterBuilder(
                        \HTML::ownNumStatus($statsCount)
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownNumStatus($tracksCount)
                    ),
                    \HTML::ownOuterBuilder(
                        \HTML::ownNumStatus(round($average))
                    )
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }



    public function getUsersStatistics()
    {
        $data = [];
        $roles = ['area-manager'];
        $fillFrom = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-month';
        $startDate =  \Services\ChartsTools::getDateFrom($fillFrom);
        $dateFill =  \Services\ChartsTools::fillDays($fillFrom);

        $users = \User::
            whereHas(
                'units', function($query) {
                $query -> whereIn('unit_id', $this -> getUnitsId());
            }) ->
            whereHas(
                'roles', function($query) use($roles) {
                $query -> whereIn('name', $roles);
            }) ->
            with (['stats'=>function($q) use($startDate){
                $q -> whereAction('log_in') -> where('created_at','>=',$startDate->format('Y-m-d 00:00:00'));
            }]) ->
            get();

        foreach($users as $user){
            $data  = [];

            $charts[$user->id] = [];
        }

        if($users)
        {
            foreach($users as $user)
            {
                $stats = $user->stats;
                $charts[$user->id] = $stats->groupBy(function($date){
                    return \Carbon::parse($date->created_at)->format('Y-m-d');
                });


                if(count($charts[$user->id])){
                    foreach($charts[$user->id] as $date => $records){
                        $dataOut[$date] = count($records);
                    }
                    $data[] = ['data'  => \Services\ChartsTools::renderDates(array_merge($dateFill,$dataOut)), 'label' => $user->fullname()];
                }
            }
        }
        return json_encode($data);
    }

    public function getSwitchDateFrom($type, $from)
    {
        if(in_array($from,['today','last-month','last-week']))
            \Session::put($type.'_from', $from);
        return \Redirect::to('/');
    }
}
