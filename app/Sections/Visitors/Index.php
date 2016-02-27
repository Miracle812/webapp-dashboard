<?php namespace Sections\Visitors;

use \Carbon\Carbon;

class Index extends VisitorsSection
{

    public function getIndex()
    {
        $foodIncidents =    $this->getEntity('FoodIncidents', false);
        $goodsIn =          $this->getEntity('TemperaturesForGoodsIn', false);
        $notifications =    NULL;//$this->getEntity('Notifications',false);
        $calendarEvents =   $this->getEntity('ComplianceDiary', false);
        $onlineUsers =      $this->getOnline('local-manager');
        $onlineVisitors =   $this->getOnline('visitor');
        $usersStatistics =  $this->getUsersStatistics();
        $pieTemperatures =  $this->getPieTemperatures();
        $flotData = [json_encode($usersStatistics[0]), $usersStatistics[1], $usersStatistics[2]];
        return \View::make($this->regView('index'), compact('breadcrumbs', 'foodIncidents', 'goodsIn', 'notifications', 'pieTemperatures', 'calendarEvents', 'onlineUsers', 'onlineVisitors', 'flotData'));
    }



    public function getEntity($model, $today = false)
    {
        $model = '\Model\\' . $model;
        $data = $model::whereIn('unit_id', $this->getUnitsId());
        if ($today)
            $data = $data->where('created_at', '>', date('Y-m-d 00:00'));
        $data = $data->limit(10)->get();
        return $data;
    }

    public function getOnline($type)
    {
        $online = \Model\Online::registered();
        if ($online->count()) {
            foreach ($online->get() as $item) {
                $user = $item->user;
                if ($user && $user->hasRole($type) and in_array($user->unit()->id, $this->getUnitsId())) {
                    $data[] = $user;
                }
            }
        }
        return isset($data) ? $data : [];
    }

    public function getPieTemperatures()
    {
        $out = [];
        $user = \Auth::user();
        $podsAreas   = \Model\TemperaturesPodsAreas::where('unit_id',$user->unitId())->whereType('area')->get();
        $probesAreas = \Model\TemperaturesProbesAreas::where('unit_id',$user->unitId())->get();
        if($podsAreas->count()){
            $out = array_merge($out, $this->getArrTemps($podsAreas,'pods'));}
        if($probesAreas->count()){
            $out = array_merge($out, $this->getArrTemps($probesAreas,'probes'));}
        return $out;
    }

    public function getArrTemps($collection,$type)
    {
        $out = [];
        $all = $invalid = $valid = $sumAll = $sumInvalid = $sumValid = 0;

        if($collection->count() <= 10) {
            foreach ($collection as $area) {
                $all = $area->temperatures;
                if ($count = $all->count()) {
                    $invalid = $all->filter(function ($item) {
                        return $item->invalid_id ? true : false;
                    });
                    $valid = $all->filter(function ($item) {
                        return !$item->invalid_id ? true : false;
                    });

                    $compliance = number_format((($valid->count() / $count) * 100), 2);

                    if ($compliance < 59)
                        $color = '#cb4b4b';
                    elseif ($compliance > 59 && $compliance < 79)
                        $color = '#ee7233';
                    else
                        $color = '#4da74d';

                    $data[] = ['label' => $area->name, 'data' => $count, 'compliance' => $compliance, 'color' => $color];
                    $out[$type] = [
                        'data' => json_encode($data),
                    ];

                    /*
                    $out[$type][] = [
                        'name' => $area->name,
                        'data' => json_encode([['label' => 'Compliant', 'data' => $valid->count(), 'color' => '#00FF00'], ['label' => 'Non Compliant', 'data' => $invalid->count(), 'color' => '#FF0000']]),
                        'amounts' => ['all' => $count, 'invalid' => $invalid->count(), 'valid' => $valid->count()]
                    ];
                    */
                }
            }
        }
        else{
            foreach ($collection as $area) {
                $all = $area->temperatures;
                if ($count = $all -> count()) {
                    $invalid = $all->filter(function ($item) {
                        return $item->invalid_id ? true : false;
                    });
                    $valid = $all->filter(function ($item) {
                        return !$item->invalid_id ? true : false;
                    });
                    $sumAll += $count;
                    $sumInvalid += $invalid->count();
                    $sumValid += $valid->count();
                }
            }
            $compliance = number_format((($sumValid / $sumAll) * 100), 2);
            if ($compliance < 59)
                $color = '#cb4b4b';//red
            elseif ($compliance > 59 && $compliance < 79)
                $color = '#ee7233';//orange
            else
                $color = '#4da74d';//green
            $out[$type] = [
                'data' => json_encode([['label' => 'All '.ucfirst($type).' Temperatures', 'data' => $sumAll, 'compliance' => $compliance, 'color' => $color]]),
            ];
        }
        return $out;
    }

    public function getUsersStatistics()
    {

        $users = \User::select('id')
            ->whereHas(
                'units', function ($query) {
                $query->whereIn('unit_id', $this->getUnitsId());
            })
            ->get()
            ->toArray();
        $dateFill = $this->fillDays('2014-09-01');
        if ($users) {
            foreach ($users as $id) {
                $usersId[] = $id['id'];
            }
            $statistics = \Model\UsersStatistics::whereIn('user_id', $usersId)->where('action', '=', 'log_in')->get();
            $visitors = $managers = 0;
            foreach ($statistics as $stat) {
                $visitors += $stat->user->hasRole('visitor') ? 1 : 0;
                $managers += $stat->user->hasRole('local-manager') ? 1 : 0;
            }

            $visitorTraffic = $statistics->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });

            if (count($visitorTraffic)) {
                foreach ($visitorTraffic as $date => $records) {
                    $dataOut[$date] = count($records);
                }
                $data = $this->renderDates(array_merge($dateFill, $dataOut));
            }
        }
        if (!isset($data)) {
            $data = $this->renderDates($dateFill);
        }
        return isset($visitors) ? [$data, $visitors, $managers] : [$data, 0, 0];
    }

    public function renderDates($input)
    {
        foreach ($input as $key => $value) {
            $data[] = [strtotime($key) * 1000, $value];
        }
        return $data;
    }

    function fillDays($sStartDate)
    {
        $sStartDate = date("Y-m-d", strtotime($sStartDate));
        $sEndDate = date("Y-m-d", strtotime('now'));
        $aDays[$sStartDate] = 0;
        $sCurrentDate = $sStartDate;
        while ($sCurrentDate < $sEndDate) {
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            $aDays[$sCurrentDate] = 0;
        }
        return $aDays;
    }
}
