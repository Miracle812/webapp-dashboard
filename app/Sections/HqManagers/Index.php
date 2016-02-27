<?php namespace Sections\HqManagers;

use \Illuminate\Support\Collection;

class Index extends HqManagersSection {

    public $section = 'index';

    public function getIndex()
    {
        $statsData = $invalidTemps = [];

        $loggons = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-week';
        $summary = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-month';

        $dateFrom = ['loggons'=>$loggons, 'summary'=>$summary];

        $foodIncidents       = $this->getEntity('FoodIncidents');
        $navinotes           = $this->getEntity('Navinotes');

        $statsData           = $this->getUsersStats();

        $dormantStats        = $this->getDormantUsersStats();
        $dormantUsers        = $dormantStats;

        $coll                = new Collection;
        $tempsNonCompGoodsIn = $this->getInvalidTemps('TemperaturesForGoodsIn');
        $tempsNonCompPods    = $this->getInvalidTemps('TemperaturesForPods');
        $tempsNonCompProbes  = $this->getInvalidTemps('TemperaturesForProbes');

        $invalidTemps = $coll->merge($tempsNonCompGoodsIn)->merge($tempsNonCompPods)->merge($tempsNonCompProbes)->sortByDesc('created_at')->take(25);

        if($invalidTemps->count()){
            foreach($invalidTemps as $key => $record){
                $record -> area_name = $this->getTempsAreaName($record);
                $invalidTemps[$key] = $record;
            }
        }
        $unitScores = $this->getUnitScores();
        $areaManagersScores = $this->getAreaManagersScores();

        return \View::make($this->regView('index'),
            compact('breadcrumbs','foodIncidents','statsData','dormantUsers',
                'invalidTemps','navinotes', 'unitScores' ,'areaManagersScores','dateFrom'));
    }

    public function getEntity($model)
    {
        $dateFrom = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-week';
        $dateFrom = \Services\ChartsTools::getDateFrom($dateFrom) -> format('Y-m-d 00:00');
        $model = '\Model\\'.$model;
        $data = $model::
        whereIn('unit_id',$this -> getUnitsId()) ->
        where('created_at','>',$dateFrom) ->
        get();
        return $data;
    }

    public function getTempsAreaName($record)
    {
        switch ($record->getTable()){
            case 'temperatures_for_pods' : $area = 'Pods temps / '. $record -> area -> name; break;
            case 'temperatures_for_probes' : $area = 'Probes temps / '. $record -> area -> name; break;
            case 'temperatures_for_goods_in' : $area = 'Goods In.'; break;
            default: $area = 'N/A'; break;
        }
        return $area;
    }

    public function getInvalidTemps($model)
    {
        $dateFrom = \Session::has('summary_from') ? \Session::get('summary_from') : 'last-week';
        $dateFrom = \Services\ChartsTools::getDateFrom($dateFrom) -> format('Y-m-d 00:00');
        $model = '\Model\\'.$model;
        $data = $model::
        whereIn('unit_id',$this -> getUnitsId()) ->
        where('created_at','>',$dateFrom) ->
        where(function ($query) {
            $query->whereNotNull('invalid_id');
            $query->where('invalid_id', '>', 0);
        }) ->
        get();
        return $data;
    }

    public function getUsersStats()
    {
        $data = [];
        $roles = ['visitor','local-manager'];
        $fillFrom = \Session::has('loggons_from') ? \Session::get('loggons_from') : 'last-month';
        $startDate = \Services\ChartsTools::getDateFrom($fillFrom);
        $dateFill = \Services\ChartsTools::fillDays($fillFrom);

        $unitsUsers = \User::
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


        foreach($roles as $role){
            $data[$role]['online'] = [];
            $data[$role]['sessions'] = 0;
            $data[$role]['traffic']  = 0;
            $data[$role]['charts']   = json_encode([]);
            $charts[$role]['charts'] = [];
        }

        if($unitsUsers)
        {
            $onlineUsersIds = \DB::table('sessions')->whereIn('user_id', $unitsUsers->lists('id'))->lists('user_id');
            foreach($unitsUsers as $user)
            {
                $roleName = $user->role()->name;
                $stats = $user->stats;
                if(in_array($user->id,$onlineUsersIds)){
                    $data[$roleName]['online'][] = $user;
                }
                $data[$roleName]['sessions'] += ($cStats = $stats -> count());
                $traffic = 0;
                if($cStats){
                    foreach ($stats as $stat){
                        $traffic += $stat->tracks ?  $stat->tracks->count() : 0;
                    }
                }
                $data[$roleName]['traffic'] += $traffic;

                $charts[$roleName]['charts'][] = $stats->groupBy(function($date){
                    return \Carbon::parse($date->created_at)->format('Y-m-d');
                });
            }
            foreach($roles as $roleName){
                $charsColl = $this -> renderCharsData($charts[$roleName]['charts']);
                $data[$roleName]['charts'] = json_encode(\Services\ChartsTools::renderDates(array_merge($dateFill,$charsColl)));
            }
        }
        return $data;
    }

    public function renderCharsData(array $stats)
    {
        $dataOut = [];
        foreach($stats as $key => $values){
            foreach($values as $data => $records){
                $count = count($records);
                $dataOut[$data] = (isset($dataOut[$data]) && ($dataOut[$data] > 0)) ? $count+$dataOut[$data] : $count;
            }
        }
        return $dataOut;
    }

    public function getDormantUsersStats()
    {
        $rolesArr = ['visitor','local-manager','area-manager'];
        $users = \User::
        whereHas(
            'units', function($query) {
            $query-> whereIn('unit_id', $this -> getUnitsId());
        })
            -> whereHas(
                'roles', function($query) use($rolesArr) {
                $query -> whereIn('name', $rolesArr);
            })
            -> whereHas(
                'stats', function($query) use($rolesArr) {
                $query -> whereAction('log_in')-> where('created_at','>',\Carbon::now('UTC')->subDays(7));
            },'=',0)
            -> orderBy('id','ASC')
            -> get();

        $unitsUsersIds = $users -> lists('id');

        $stats =  \Model\UsersStatistics::
        whereIn('user_id', $unitsUsersIds)
            -> whereIn('role', $rolesArr)
            -> whereRaw('id IN (SELECT max(id) FROM users_statistics GROUP BY user_id)')
            -> get();
        $statsUsersIds = $stats->lists('user_id');

        $users = $users->filter(function ($users) use ($statsUsersIds)
        {
            return !in_array($users->id,$statsUsersIds);
        });
        $coll  = new Collection;
        $users = $coll->merge($users)->merge($stats);
        return $users;
    }

    public function getUnitScores()
    {
        $service = new \Services\Scores();
        foreach($this -> getUnitsId() as $unitId){
            $service->checkStart($unitId);
        }
        return \Model\Scores::whereIn('unit_id',$this -> getUnitsId())->whereRaw('id IN (SELECT max(id) FROM scores GROUP BY unit_id)')->orderBy('created_at','DESC')->get();
    }

    public function getAreaManagersScores()
    {
        $headquarter = $this->headquarter;
        $areaManagers = \User::
            whereHas(
                'units', function($query) use($headquarter){
                $query-> whereIn('unit_id', $headquarter -> units -> lists('id'));
            }) -> whereHas(
                'roles', function($query) {
                $query -> whereName('area-manager');
            }) -> get();

        $collection = new \Illuminate\Database\Eloquent\Collection;
        $startPoints = \Config::get('scores.points.start');

        foreach($areaManagers as $manager)
        {
            $unitsIds = $manager->units->lists('id');
            $scores = \Model\Scores::whereIn('unit_id',$unitsIds)->whereRaw('id IN (SELECT max(id) FROM scores GROUP BY unit_id)')->orderBy('created_at','DESC')->get();
            $scoresArr = $scores->lists('scores');
            $scoresSummary = array_sum($scoresArr);
            $startPointsSum = (count($scoresArr)*$startPoints);
            $scoresPercent = number_format((($scoresSummary/$startPointsSum)*100),1);
            $manager -> scores = $scores;
            $manager -> scores_percent = $scoresPercent;
            $collection = $collection -> add($manager);
        }
        return $collection;
    }

    public function getPercentCompliantData($id)
    {
        $data = $datas = $totalz = [];
        if(in_array($id, $this->getUnitsId())){
            $scores = \Model\Scores::whereUnitId($id)->where('created_at','>',\Carbon::now('UTC')->startOfMonth())->get();
            $items = $scores->lists('target_type');

            if(($key = array_search('units', $items )) !== false) {
                unset($items [$key]);
            }

            foreach ($items as $item){
                $totalz = [];
                foreach($scores as $score){
                    if($score->target_type == $item){
                        if($score->target_type == 'temperatures_for_pods'){
                            $target = $score->target();
                            if(isset($total[$item][$target->area_id])){$total[$item][$target->area_id] += 1;}
                            else{$total[$item][$target->area_id] = 1;}
                            $datas[$score->id.$target->area->id]=['label' => $target->area->name, 'data'=> $total[$item][$target->area_id]];
                        }
                        else{
                            if(isset($totalz[$item])){$totalz[$item] += 1;}
                            else{$totalz[$item] = 1;}
                            $datas[$item]=['label' => \Lang::get('common/sections.'.$item.'.title'), 'data'=> $totalz[$item]];
                        }
                    }
                }
            }
            foreach($datas as $dat){
                $data[] = $dat;
            }
        }

        $data = empty($data) ? [['label' => 'Start', 'data'=> 1]] : $data;

        return
            \Response::json(
                $data
            );
    }

    public function getSwitchDateFrom($type, $from)
    {
        if(in_array($from,['today','last-month','last-week']))
            \Session::put($type.'_from', $from);
        return \Redirect::back();
    }
}