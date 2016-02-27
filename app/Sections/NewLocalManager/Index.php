<?php namespace Sections\NewLocalManager;
class Index extends BaseSection {

    public function getIndex()
    {
        $options=['schedules'=>$this->localQueries('schedules')->count(),'checklist'=>$this->localQueries('checklist')->count()];
        $folders = \Model\TemperaturesAlertBox::where('unit_id','=',\Auth::user()->unit()->id) -> whereNull('parent_id')->get();
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Dashboard', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs','folders','options'));
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
        $schedules = $this->localQueries('schedules')->select(['cleaning_schedules_tasks.title','cleaning_schedules_items.end']);
        return \Datatables::of($schedules)
            ->edit_column('cleaning_schedules_items.end', function ($item) {
                return \Carbon::parse($item->end)->format('d-m-Y');
            })
            ->add_column('resolve','<button class="md-btn md-btn-flat md-btn-success">Resolve</button>')
            ->make();
    }

    public function getDtCheckList()
    {
        $checklists = $this->localQueries('checklist');
        return \Datatables::of($checklists)
            ->edit_column('check_list_items.end', function ($checklist) {
                return \Carbon::parse($checklist->end)->format('d-m-Y');
            })
            ->add_column('resolve','<button class="md-btn md-btn-flat md-btn-success">Resolve</button>')
            ->make();
    }

    public function localQueries($section)
    {
        if($section == 'schedules'){
            $name = 'new_cleaning_schedules_';
            return \DB::table($name.'tasks2')->
                where($name.'tasks2.unit_id',\Auth::user()->unitId())->
                join($name.'items2',$name.'tasks2.id','=',$name.'items2.task_id')->
                select([$name.'tasks2.title',$name.'items2.end'])->
                where($name.'items2.start','<=',\Carbon::now())->
                where($name.'items2.end','>=',\Carbon::now());
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
        return null;
    }
}