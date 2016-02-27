<?php namespace Sections\LocalManagers;

use Symfony\Component\HttpFoundation\Request;

class Temperatures extends LocalManagersSection {

    protected $tService;
    protected $emptyDatatable = ['aaData' => []];

    public function __construct(\Services\Temperatures $tService){
        parent::__construct();
        $this -> tService = $tService;
    }

    public function getIndex()
    {
        return \View::make($this->regView('index'));
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
            if(!$area){
                $area -> createUnitProbeAreas($unitId);
                $area = $area -> getTargetArea($unitId);
            }
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

    public function getFormsIndex()
    {
        $assignedForms = new \Model\AssignedForms();
        $navitasForms  = $assignedForms -> getFormsBySelect(7,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(7,'units');
        $this -> breadcrumbs -> addCrumb('temperatures/forms', 'Temperatures forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast(  $this -> setAction('Forms list',false) );
        return \View::make($this->regView('forms.index'), compact('unitForms','navitasForms','breadcrumbs'));
    }

    public function getFormsSubmittedList()
    {
        $this -> breadcrumbs -> addCrumb('temperatures/forms', 'temperatures forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted tasks list', false) );
        return \View::make($this->regView('forms.submitted.list'), compact( 'breadcrumbs'));
    }

    public function getFormsSubmittedDetails($id)
    {
        $submitted = \Model\FormsAnswers::with('formLog')->find($id);
        $formHTml = $submitted ?
            \App::make('\Modules\FormBuilder')->getDisplay($submitted->id,'render')
            : '';
        return  \View::make($this->regView('forms.submitted.details'), compact('submitted','formHTml'));
    }

    public function getFormsDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $answers = \Model\FormsAnswers::whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 7);
        })-> whereUnitId($unitId)->orderBy('id','desc')->get();
        $answers = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $answers) : $answers -> take(100);
        $options = [];
        if($answers)
            foreach ($answers as $answer)
            {
                $opt = unserialize($answer->options);
                $options[] = [
                    strtotime($answer -> created_at),
                    $answer->created_at(),
                    $answer->formLog->name,
                    isset($opt['compliant']) ? $opt['compliant'] : 'N/A Not implemented',
                    \HTML::mdOwnOuterBuilder(
                        \HTML::mdActionButton($answer->id.'/details','temperatures/forms','submitted','search', 'Search')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }

    public function getResolveAreaTemperatures($group,$areaId)
    {
        \Session::put('ref-url',\URL::previous());
        switch($group){
            case 'probes':$table = 'temperatures_for_probes'; break;
            case 'pods':$table = 'temperatures_for_pods'; break;
            default : $table = null; break;
        }
        $area = \DB::table('temperatures_'.$group.'_areas')->find($areaId);
        $temperatures = \DB::table($table)->
        select(['*', $table.'.id as temp_id'])->
        where($table.'.unit_id',\Auth::user()->unitId())->
            where($table.'.invalid_id','>',0)->
            where($table.'.area_id',$areaId)->
            where(function($queryA)use($table){
                $queryA->
                where(function($queryB)use($table){
                    $queryB->where($table.'.resolved_id','=',0)->
                    orWhere($table.'.resolved_id','=',null);})->
                orWhere(function($query3)use($table){
                    $query3->where($table.'.resolved_id','>',0)->where('temperatures_resolved.resolved',0);
                });
            })->
            leftJoin('temperatures_resolved',$table.'.resolved_id','=','temperatures_resolved.id')->
            join('temperatures_'.$group.'_areas',$table.'.area_id','=','temperatures_'.$group.'_areas.id')->
            orderBy($table.'.id','DESC')->
            get();
        return  \View::make($this->regView($group.'.resolve'), compact('temperatures','area'));

    }

    public function postResolveAreaTemperatures($group,$areaId)
    {
        $rules = ['temperatures'=>'required','comment'=>'required'];
        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if($errors) {
            return \Response::json(['type' => 'error', 'form_errors' => $this->ajaxErrors($errors, [])]);
        }
        switch($group){
            case 'probes':
                $table = 'temperatures_for_pods';
                $model = '\Model\TemperaturesForProbes';
                break;
            case 'pods':
                $table = 'temperatures_for_pods';
                $model = '\Model\TemperaturesForPods';
                break;
            default : $table = null; break;
        }
        if($table){
            $resolve = \Model\TemperaturesResolved::create(['comment'=>\Input::get('comment'),'resolved'=>1,'unit_id'=>\Auth::user()->unitId()]);
            $model::whereIn('id',\Input::get('temperatures'))->update(['resolved_id'=>$resolve->id]);
            return \Response::json(['type'=>'success','redirect'=>(\Session::get('ref-url')?:'/')]);
        }
    }
}