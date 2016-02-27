<?php namespace Sections\Visitors;

use Symfony\Component\HttpFoundation\Request;

class Temperatures extends VisitorsSection {

    protected $tService;
    protected $emptyDatatable = ['aaData' => []];

    public function __construct(\Services\Temperatures $tService){
        parent::__construct();
        $this -> tService = $tService;
        $this -> breadcrumbs -> addCrumb('temperatures', 'Temperatures');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }
//groups
    public function getAreasTemperatures($root, $range = 'last-100')
    {
        $unitId = $this->auth_user->unitId();

        if($root == 'pods')
            $areas = \Model\TemperaturesPodsAreas::whereUnitId($unitId)->whereType('area')->get();
        else
            $areas = \Model\TemperaturesProbesAreas::whereUnitId($unitId)->get();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView($root.'.temperatures-group'), compact('breadcrumbs','areas', 'range'));
    }

    public function getDatatableAreas($group, $range = 'last-100')
    {
        $tService = $this -> tService;
        $limit = $range == 'last-100' ? 100 : NULL;

        $dRules = array_merge($tService -> getRangeDates($range),['data_range'=>'last-values']);

        if($filter = \Input::get('datatable')){
            $dRules = [
                'from' => \Carbon::createFromFormat('Y-m-d', $filter['date_from'])->startOfDay(),
                'to'   => \Carbon::createFromFormat('Y-m-d', $filter['date_to'])->endOfDay(),
                'data_range'=>$range];
        }
        $entity = $tService -> getTemperaturesEntity($group, null, $dRules, $limit);

        $entity = ($filter) ? \Mapic::datatableFilter($filter, $entity) : $entity -> take(100);
        return $this->returnDatatable($entity,['group'=>$group]);
    }

    public function getAreaTemperatures($group, $area_id, $range = 'last-100')
    {
        $unitId = $this->auth_user->unitId();
        switch ($group){
            case 'pods' : $area = \Model\TemperaturesPodsAreas::find($area_id); break;
            case 'probes' : $area = \Model\TemperaturesProbesAreas::find($area_id); break;
            default : $area = NULL; break;
        }
        $chilling =  '';
        if($area && $group == 'probes'){
            $area = $area -> getTargetArea($unitId);
            $chilling = $area -> isChilling() ? '-chilling' : '';
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView($group.'.temperatures-list'.$chilling), compact('breadcrumbs','group', 'area', 'range'));
    }

    public function getDatatableArea($group=null, $area_id, $range = 'last-100')
    {
        $tService = $this -> tService;
        $limit = $range == 'last-100' ? 100 : NULL;
        $dRules = array_merge($tService -> getRangeDates($range),['data_range'=>'all-values']);
        $entity = $tService -> getTemperaturesEntity($group, $area_id, $dRules, $limit);
        $entity = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $entity) : $entity -> take(100);
        return $this->returnDatatable($entity,['area'=>$area_id]);
    }
//return
    public function returnDatatable($entity,$type){
        $emptyJson = json_encode($this->emptyDatatable);
        if (!$entity->count())
            return $emptyJson;
        $options = $this -> tService -> contentBuilder($entity,$type);
        return  $options ? json_encode(['aaData' => $options]) : $emptyJson;
    }

    public function getCreate(){
        $unitId = $this->auth_user->unitId();
        $pods = \Model\TemperaturesPodsSensors::whereUnitId($unitId)->get();
        $areas = \Model\TemperaturesPodsAreas::whereUnitId($unitId)->whereType('area')->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs','areas','pods'));
    }

    public function postCreate()
    {
        $new = new \Model\TemperaturesForPods();
        $rules = $new -> rules;
        $inputs = \Input::all();
        $validator = \Validator::make($inputs, $rules);

        if(!$validator -> fails()) {

            $pod = \Model\TemperaturesPodsSensors::find(\Input::get('pod_id'));

            $area = $pod ? $pod -> area() : null;

            if($pod && $area)
            {
                $excluded = $area->excludeTimeframe;
                $excluded = $excluded ? $excluded->isExcluded() : false;
                if($excluded) {
                    return \Redirect::back()->withErrors(['Storing temperatures is excluded by time frame for this area.']);
                }
                $timestamp = \Carbon::now()->timestamp;


                $new = new \Model\TemperaturesForPods();

                $new -> area_id   = $area-> id;
                $new -> unit_id   = $area-> unit_id;
                $new -> pod_id    = $pod -> id;
                $new -> pod_ident = $pod -> identifier;
                $new -> pod_name  = $pod -> name;
                $new -> timestamp = $timestamp;
                $new -> temperature = \Input::get('temperature');
                $new -> battery_level     = 100;
                $new -> battery_voltage   = \Input::get('voltage');

                $save = $new -> save();

                $tempService = new \Services\Temperatures();
                $tempService->commonVerifier($area, $new);
                $type = $save ? 'success' : 'fail';
                $msg  = \Lang::get('/common/messages.create_'.$type);
                return \Redirect::to('/')->with($type, $msg);
            }
            else {
                return \Redirect::back()->withErrors(['Pod not found or area not assigned to pod sensor.']);
            }
        }

        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }
}