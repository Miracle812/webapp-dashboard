<?php namespace Sections\Admins;
use \Carbon\Carbon;
class Index extends AdminsSection {

    public function getIndex()
    {
        $loggons = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-week';
        $summary = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-month';

        $dateFrom = ['loggons'=>$loggons, 'summary'=>$summary];
        $foodIncidents      = $this->getEntity('FoodIncidents');
        $goodsIn            = $this->getEntity('TemperaturesForGoodsIn');
        $notifications      = NULL;//$this->getEntity('Notifications');
        $calendarEvents     = $this->getEntity('ComplianceDiary');
        $onlineUsers        = $this->getOnline('local-manager');
        $onlineVisitors     = $this->getOnline('visitor');
        $usersStatistics    = $this->getUsersStatistics();

        $flotData           = [json_encode($usersStatistics[0]), $usersStatistics[1], $usersStatistics[2], (isset($usersStatistics[3])?$usersStatistics[3]:0)];

        return \View::make($this->regView('index'), compact('breadcrumbs','foodIncidents','goodsIn','notifications','calendarEvents','onlineUsers','onlineVisitors','flotData','dateFrom'));
    }

    public function getEntity($model)
    {
        $dateFrom = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-week';
        $dateFrom = $this->getDateFrom($dateFrom) -> format('Y-m-d 00:00');
        $model = '\Model\\'.$model;
        $data = $model::

            where('created_at','>',$dateFrom) ->
            get();
        return $data;
    }

    public function getOnline($type)
    {
        $online = \Model\Online::registered();
        if($online->count()){
            foreach ($online->get() as $item){
                $user = $item->user;
                if ($user && $user -> hasRole($type)){
                    $data[] = $user;
                }
            }
        }
        return isset($data)?$data:[];
    }

    public function getUsersStatistics()
    {
        $users = \User::
        whereHas(
            'roles', function($query) {
                $query-> whereIn('name', ['area-manager','local-manager','hq-manager','visitor']);
            })
            -> lists('id');

        $fillFrom = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-month';
        $sStartDate = $this->getDateFrom($fillFrom);
        $dateFill = $this->fillDays($fillFrom);
        if($users){
            $statistics = \Model\UsersStatistics::whereIn('user_id', $users)
                -> where('action','=','log_in')
                -> where('created_at','>=',$sStartDate->format('Y-m-d 00:00:00'))
                -> get();

            $visitors = $localManagers = $hqManagers = 0;
            foreach($statistics as $stat){
                $visitors += $stat -> user -> hasRole('visitor') ? 1 : 0;
                $localManagers += $stat -> user -> hasRole('local-manager') ? 1 : 0;
                $hqManagers += $stat -> user -> hasRole('hq-manager') ? 1 : 0;
            }

            $visitorTraffic = $statistics->groupBy(function($date){
                return \Carbon::parse($date->created_at)->format('Y-m-d');
            });

            if(count($visitorTraffic)){
                foreach($visitorTraffic as $date => $records){
                    $dataOut[$date] = count($records);
                }
                $data = $this->renderDates(array_merge($dateFill,$dataOut));
            }
        }
        if(!isset($data)){
            $data = $this->renderDates($dateFill);
        }
        return isset($visitors) ? [$data,$visitors,$localManagers,$hqManagers] : [$data,0,0];
    }

    public function renderDates($input){
        foreach ($input as $key => $value){
            $data[] = [strtotime($key)*1000, $value];
        }
        return $data;
    }

    function fillDays($fillFrom = 'last-month'){
        $sStartDate = $this->getDateFrom($fillFrom);
        $sEndDate = \Carbon::now();

        $aDays[$sStartDate->toDateString()] = 0;
        $sCurrentDate = $sStartDate;
        while($sCurrentDate < $sEndDate){
            $sCurrentDate = $sCurrentDate->addDay();
            $aDays[$sCurrentDate->toDateString()] = 0;
        }
        return $aDays;
    }

    public function getDateFrom($from = 'last-month')
    {
        switch($from){
            case 'today': $from = \Carbon::now(); break;
            case 'last-month': $from = \Carbon::now()->subMonth(); break;
            case 'last-week' : $from = \Carbon::now()->subWeek();  break;
            default : $from = \Carbon::now()->subMonth(); break;
        }
        return $from;
    }

    public function getSwitchDateFrom($type, $from)
    {
        if(in_array($from,['today','last-month','last-week']))
            \Session::put($type.'_from', $from);
        return \Redirect::to('/');
    }
}
