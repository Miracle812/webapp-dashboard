<?php namespace Sections\LocalManagers;

class Index extends LocalManagersSection {

    public function getIndex()
    {
        $options = [];
        foreach(['schedules','checklist','compliancediary','pods','probes'] as $key){
            $options[$key] = $this->localQueries($key)->count();
        }
        $folders = \Model\TemperaturesAlertBox::where('unit_id','=',\Auth::user()->unit()->id) -> whereNull('parent_id')->get();
        return \View::make($this->regView('index'), compact('folders','options'));
    }

    public function getTempsWidgetData($id)
    {
        $record = \Model\TemperaturesAlertBox::find($id);
        $area = $record->area;

        $graphTemps = $area->temperatures()
            ->select([\DB::Raw('DATE(created_at) as date'),\DB::raw('count(*) as countTemps'),\DB::raw('SUM(temperature) as sumTemps')])
            ->groupBy('date')
            ->orderBy('id','DESC')
            ->limit(100)
            ->get();

        $temps = $donut = $volts = [];
        foreach($graphTemps as $graphTemp){
            $temps[] = ['date'=>$graphTemp->date, 'value' => ($graphTemp->sumTemps / $graphTemp->countTemps)];
        };
        if($record->group == 'pods') {
            $graphBatts = $area->temperatures()
                ->select([\DB::Raw('DATE(created_at) as date'), \DB::raw('count(*) as countVolts'), \DB::raw('SUM(battery_voltage) as sumVolts')])
                ->groupBy('date')
                ->orderBy('id','DESC')
                ->limit(100)
                ->get();
            foreach($graphBatts as $graphBatt){
                $volts[] = ['date'=>$graphBatt->date, 'value' => ($graphBatt->sumVolts / $graphBatt->countVolts)];
            };
        }

        $todayTemps = $area->getTodayTemperatures();
        $tvalid = $tinvalid = 0;
        if($todayTemps->count()) {
            foreach ($todayTemps as $todayTemp) {
                $repo = $todayTemp->repository();
                if ($repo->isInvalid()) {
                    $tinvalid += 1;
                } else {
                    $tvalid += 1;
                }
            };
        }
        $donut = ['invalid' => $tinvalid, 'valid' => $tvalid];

        $lastTemp = $area->getLastTemperature();
        $ltVal = $lastTemp ? $lastTemp->temperature : 'N/A';
        $ltDate =  $lastTemp ? $lastTemp->created_at: 'N/A';

        $srv = new \Services\Temperatures();
        $ltInfo = $lastTemp ? $srv->popoverContentBuilder($lastTemp): 'N/A';

        return \Response::json(['records'=>$temps,'donutpie'=>$donut,'area'=>['name'=>$area->name],'rule'=>['valid_min'=>$area->valid_min,'valid_max'=>$area->valid_max],'last_temp'=>['date'=>\Carbon::parse($ltDate)->format('d-m-Y H:i'), 'info'=>$ltInfo,'val'=>$ltVal]]);
    }

    public function getDtSchedules()
    {
        $schedules = $this->localQueries('schedules')->select(['cleaning_schedules_tasks.title','cleaning_schedules_items.end','cleaning_schedules_items.id']);
        return \Datatables::of($schedules)
            ->edit_column('cleaning_schedules_items.end', function ($item) {
                return \Carbon::parse($item->end)->format('d-m-Y');
            })
            ->add_column('resolve', function ($item) {
                return '<a href="/cleaning-schedule/complete/'.$item->id.'" class="md-btn md-btn-flat md-btn-success">Resolve</a>';
            })
            ->remove_column('id')
            ->make();
    }

    public function getDtCheckList()
    {
        $checklists = $this->localQueries('checklist')->select(['check_list_tasks.title','check_list_items.end','check_list_items.id']);
        return \Datatables::of($checklists)
            ->edit_column('check_list_items.end', function ($item) {
                return \Carbon::parse($item->end)->format('d-m-Y');
            })
            ->add_column('resolve', function ($item) {
                return '<a href="/check-list/complete/'.$item->id.'" class="md-btn md-btn-flat md-btn-success">Resolve</a>';
            })
            ->remove_column('id')
            ->make();
    }

    public function getDtComplianceDiary()
    {
        $checklists = $this->localQueries('compliancediary')->select(['compliance_diary_tasks.title','compliance_diary_items.end','compliance_diary_items.id']);
        return \Datatables::of($checklists)
            ->edit_column('check_list_items.end', function ($item) {
                return \Carbon::parse($item->end)->format('d-m-Y');
            })
            ->add_column('resolve', function ($item) {
                return '<a href="/compliance-diary/complete/'.$item->id.'" class="md-btn md-btn-flat md-btn-success">Resolve</a>';
            })
            ->remove_column('id')
            ->make();
    }

    public function getDtPods()
    {
        $temperatures = $this->localQueries('pods')->select([
            'temperatures_for_pods.id as pod_id',
            'temperatures_for_pods.timestamp as pod_timestamp',
            'temperatures_pods_areas.name as pod_area_name',
            'temperatures_for_pods.temperature as pod_temperature',
            'temperatures_pods_areas.id as pod_area_id',
        ])->orderBy('temperatures_for_pods.id','DESC');
        return \Datatables::of($temperatures)
            ->edit_column('pod_timestamp', function ($item) {
                return \Carbon::createFromTimestamp($item->pod_timestamp)->format('d-m-Y H:i');
            })
            ->edit_column('pod_temperature', function ($item) {
                return $item->pod_temperature;
            })
            ->add_column('resolve', function ($item) {
                return '<a href="/temperatures/pods/resolve/area/'.$item->pod_area_id.'" class="md-btn md-btn-flat md-btn-success">Resolve</a>';
            })
            ->remove_column('pod_id')
            ->remove_column('pod_area_id')
            ->make();
    }

    public function getDtProbes()
    {
        $temperatures = $this->localQueries('probes')->select([
            'temperatures_for_probes.id as probe_id',
            'temperatures_for_probes.created_at as probe_created_at',
            'temperatures_probes_areas.name as probe_area_name',
            'temperatures_for_probes.temperature as probe_temperature',
            'temperatures_probes_areas.id as probe_area_id',
        ])->orderBy('temperatures_for_probes.id','DESC');
        return \Datatables::of($temperatures)
            ->edit_column('probe_created_at', function ($item) {
                return \Carbon::parse($item->probe_created_at)->format('d-m-Y H:i');
            })
            ->edit_column('probe_temperature', function ($item) {
                return $item->probe_temperature;
            })
            ->add_column('resolve', function ($item) {
                return '<a href="/temperatures/probes/resolve/area/'.$item->probe_area_id.'" class="md-btn md-btn-flat md-btn-success">Resolve</a>';
            })
            ->remove_column('probe_id')
            ->remove_column('probe_area_id')
            ->make();
    }

    public function postDtLastTemps()
    {
        $record = \Model\TemperaturesAlertBox::find(\Input::get('area_id'));
        $area = $record->area;
        $temperatures = $area->temperatures()
            ->select('created_at','temperature','pod_ident','battery_voltage','rules_id');
        return \Datatables::of($temperatures)
            ->edit_column('temperature', function ($temperature) {
                $class =  ($temperature->invalid_id ? 'md-color-red-700 uk-text-bold' : 'md-color-green-700');
                return '<span class="'.$class.'">'.$temperature->temperature.'&#x2103</span>';
            })
            ->edit_column('pod_ident', function ($temperature) {
                return '<span data-uk-tooltip="{cls:\'uk-text-small\'}" title="'.$temperature->pod_ident.'" class="small">'.$temperature->pod_ident.'</span>';
            })
            ->add_column('valid_range', function ($temperature) {
                $rule = $temperature->rule;
                return $rule->valid_min.' - '.$rule->valid_max;
            })
            ->add_column('battery_voltage', function ($temperature) {
                $class = 'uk-text-success';
                //if($temperature->battery_voltage <= 3.5) $class = 'uk-text-success';
                if($temperature->battery_voltage <= 2.8) $class = 'uk-text-warning';
                if($temperature->battery_voltage <= 2.3) $class = 'uk-text-danger';
                return '<span class="'.$class.'">'.$temperature->battery_voltage.'V</span>';
            })
            ->remove_column('rules_id')
            ->make();
    }

    public function localQueries($section)
    {
        if($section == 'schedules'){
            $name = 'cleaning_schedules_';
            return \DB::table($name.'tasks')->
                where($name.'tasks.unit_id',\Auth::user()->unitId())->
                join($name.'items',$name.'tasks.id','=',$name.'items.task_id')->
                select([$name.'tasks.title',$name.'items.end'])->
                where($name.'items.start','<=',\Carbon::now())->
                where($name.'items.end','>=',\Carbon::now());
        }
        if($section == 'compliancediary'){
            $name = 'compliance_diary_';
            return \DB::table($name.'tasks')->
                where($name.'tasks.unit_id',\Auth::user()->unitId())->
                join($name.'items',$name.'tasks.id','=',$name.'items.task_id')->
                select([$name.'tasks.title',$name.'items.end'])->
                where($name.'items.start','<=',\Carbon::now())->
                where($name.'items.end','>=',\Carbon::now());
        }
        if($section == 'checklist'){
            $name = 'check_list_';
            return
                \DB::table($name.'tasks')->
                where($name.'tasks.unit_id',\Auth::user()->unitId())->
                join($name.'items',$name.'tasks.id','=',$name.'items.task_id')->
                select([$name.'tasks.title',$name.'items.end'])->
                where($name.'items.start','<=',\Carbon::now())->
                where($name.'items.end','>=',\Carbon::now());
        }
        if($section == 'probes'){
            $name = 'temperatures_for_probes';
            return
                \DB::table($name)->
                where($name.'.unit_id',\Auth::user()->unitId())->
                where($name.'.invalid_id','>',0)->
                where(function($queryA)use($name){
                    $queryA->
                    where(function($queryB)use($name){
                        $queryB->where($name.'.resolved_id','=',0)->
                        orWhere($name.'.resolved_id','=',null);})->
                    orWhere(function($query3)use($name){
                        $query3->where($name.'.resolved_id','>',0)->where('temperatures_resolved.resolved',0);
                    });
                })->
                leftJoin('temperatures_resolved',$name.'.resolved_id','=','temperatures_resolved.id')->
                join('temperatures_probes_areas',$name.'.area_id','=','temperatures_probes_areas.id')->
                whereRaw($name.'.id IN (SELECT MAX('.$name.'.id) FROM '.$name.' WHERE '.$name.'.invalid_id > 0 GROUP BY '.$name.'.area_id)');
        }

        if($section == 'pods'){
            $name = 'temperatures_for_pods';
            return
                \DB::table($name)->
                    where($name.'.unit_id',\Auth::user()->unitId())->
                    where($name.'.invalid_id','>',0)->
                    where(function($queryA)use($name){
                        $queryA->
                        where(function($queryB)use($name){
                            $queryB->where($name.'.resolved_id','=',0)->
                            orWhere($name.'.resolved_id','=',null);})->
                        orWhere(function($query3)use($name){
                            $query3->where($name.'.resolved_id','>',0)->where('temperatures_resolved.resolved',0);
                        });
                    })->
                leftJoin('temperatures_resolved',$name.'.resolved_id','=','temperatures_resolved.id')->
                join('temperatures_pods_areas',$name.'.area_id','=','temperatures_pods_areas.id')->
                whereRaw($name.'.id IN (SELECT MAX('.$name.'.id) FROM '.$name.' WHERE '.$name.'.invalid_id > 0 GROUP BY '.$name.'.area_id)');

        }
        return null;
    }
}