<?php namespace Sections\LocalManagers;

class PodsAreas extends LocalManagersSection {

    protected $areas;
    protected $_root;

    public function __construct(\Model\TemperaturesPodsAreas $areas)
    {
        parent::__construct();
        $this  -> areas = $areas;
        $this  -> _root = $areas -> getRoot();
        $this -> breadcrumbs -> addCrumb('pods/areas', 'Pod Areas');
    }

    public function getIndex()
    {
        $areas = $this -> areas;
        $maxLevels = 3;
        $root = $this -> _root;

        $tree = $areas -> getTreeFromDB();
         $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','areas','maxLevels','root','tree'));
    }

    public function getRefresh()
    {
        $areas = $this -> areas;
        $pageItems = $areas -> getTreeFromDB();
        $first = false;
        $refresh = 1;
        return \View::make('newlayout.partials.pods_areas_nestable_tree', compact('pageItems','refresh','first'));
    }

    public function getCreate($type = null)
    {
        $unitId = \Auth::user()->unitId();
        $pods = \Model\TemperaturesPodsSensors::whereUnitId($unitId)->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView($type == 'group' ? 'modal.create-group' : 'create'), compact('breadcrumbs','pods'));
    }

    public function postCreate($type = null)
    {
        $type = $type == 'group' ? $type : 'area';
        \Input::merge(['type'=>$type]);
        $new   = $this -> areas;
        $rules = $new -> rules;
        $rules = $rules[$type];
        $input = \Input::all();
        $sensorIdent = \Input::get('sensor_identifier');
        $sensorName  = \Input::get('sensor_name');

        if( $type == 'area' ){
            $rules['sensor_name'] = 'required|max:20';
            $rules['sensor_identifier'] = 'required|between:3,30|unique:temperatures_pods_sensors,identifier';
        }

        $dropPods = [];

        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $root =  $this      -> _root;
            $new  -> lft        = $root -> rgt;
            $new  -> rgt        = $root -> rgt+1;
            $new  -> root       = $root -> id;
            $new  -> lvl        = 1;
            $new  -> parent_id  = $root->id;
            $new  -> unit_id    = $root->unit_id;
            $new  -> sort       = 0;
            $save = $new -> save();




            if( $type == 'area' ){
                if(\Input::get('timeframe.on') && \Input::get('timeframe.on') > 0){
                    \Model\TemperaturesPodsTimeExclude::create([
                        'unit_id'=>$new  -> unit_id,
                        'area_id'=>$new  -> id,
                        'week_days'=>implode(',',\Input::get('timeframe.days')),
                        'all_day'=>\Input::get('timeframe.allday'),
                        'from'=>\Input::get('timeframe.from'),
                        'to'=>\Input::get('timeframe.to')
                    ]);
                }
                $pod = \Model\TemperaturesPodsSensors::where('identifier',$sensorIdent)->first();
                if(!$pod){
                    $pod = \Model\TemperaturesPodsSensors::create(['unit_id' => $root->unit_id, 'identifier'=>$sensorIdent,'name'=>$sensorName]);
                    $dropPods[] = $pod->id;
                }
            }

            $new -> savePods($dropPods);
            $type = $save ? 'success' : 'fail';
            $msg  =  \Lang::get('/common/messages.create_'.$type);
            return (\Request::ajax()) ?
                \Response::json(['type' => $type, 'msg' => $msg]) :
                \Redirect::to('/pods/areas')->with($type, [$msg, '<br> Sensor '.$sensorIdent.' is now assigned to '.$new->name]);
        }
        else
        {
            if (\Request::ajax()) {
                $errors = $validator -> messages() -> toArray();
                return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.create_fail'), 'errors' => $this->ajaxErrors($errors, [])]);
            }
            else{
                return \Redirect::back()->withInput()->withErrors($validator);
            }
        }
    }

    public function getEdit($area)
    {
        if(!$area || !$area->checkAccess())
            return $this -> redirectIfNotExist();

        $allPods = \Model\TemperaturesPodsSensors::whereUnitId($area->unit_id)->get();
        $podsAssign = $area -> pods;
        $podsNotAssign = $allPods -> diff($podsAssign);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','area','podsAssign','podsNotAssign' ));
    }

    public function postEdit($area)
    {
        if(!$area || !$area->checkAccess())
            return $this -> redirectIfNotExist();
        $rules = $area -> rules;
        $rules = $rules['area'];
        unset($rules['type']);
        $sensorIdent = \Input::get('sensor_identifier');
        $sensorName  = \Input::get('sensor_name');

        if( !empty($sensorIdent) || !empty($sensorName) )
        {
            $rules['sensor_name'] = 'required|max:20';
            $rules['sensor_identifier'] = 'required|between:3,30|unique:temperatures_pods_sensors,identifier,'.$area->id;
        }
        //$droppedPods = json_decode(\Input::get('assigned_pods'));
        //$dropPods = [];
        //if($droppedPods && count($droppedPods)>0)
        //    foreach($droppedPods as $dropPod){
        //        $dropPods[] = $dropPod->id;
        //    };
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $area -> fill($input);
            $save = $area -> update();

            \Model\TemperaturesPodsTimeExclude::firstOrCreate([
                'unit_id'=>$area  -> unit_id,
                'area_id'=>$area  -> id
            ]) -> update ([
                'week_days'=>implode(',',\Input::get('timeframe.days')?:[]),
                'active'=>(\Input::get('timeframe.on')?:0),
                'all_day'=>\Input::get('timeframe.allday'?:0),
                'from'=>\Input::get('timeframe.from'),
                'to'=>\Input::get('timeframe.to')]);


            //if($sensorIdent && $sensorName){
            //    $pod = \Model\TemperaturesPodsSensors::where('identifier',$sensorIdent)->first();
            //    if(!$pod){
            //        $pod = \Model\TemperaturesPodsSensors::create(['unit_id' => $area->unit_id, 'identifier'=>$sensorIdent,'name'=>$sensorName]);
            //        $dropPods[] = $pod->id;
            //    }
            //}
            //$area -> savePods($dropPods);
            $type = $save ? 'success' : 'fail';
            $msg  =  \Lang::get('/common/messages.update_'.$type);
            return \Redirect::to('/pods/areas')->with($type, $msg);
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postUpdateOrder($child = null, $parent = null, $depth = 0)
    {
        ++$depth;
        $sort = 0;
        $sortlist = $child ? : \Input::all();
        if(!$child){
            $err = $this->checkNames($sortlist);
            if($err)
                return \Response::json($err);
        }
        $root = $this->_root;
        if(count($sortlist)){
            foreach($sortlist as $item){
                ++$sort;
                $entity = $this->areas->find($item['id']);
                if($entity){
                    if(isset($item['name'])){
                        $entity -> name = $item['name'];
                    }
                    $entity->lvl =  $root->id == $item['id'] ? 0 : $depth;
                    $entity->sort = $sort;
                    $entity->parent_id = $depth==1 ? $root->id : $parent;
                    $entity->update();
                }
                if(isset($item['children'])) {
                    $this->postUpdateOrder($item['children'], $item['id'], $depth);
                }
            }
        }
        return \Response::json(['type' => 'success', 'msg' => 'Update completed']);
    }

    public function checkNames($sortlist = null)
    {
        $rules = $this -> areas -> rules['group'];
        foreach($sortlist as $item){
            if(isset($item['name'])){
                $validator = \Validator::make(['name'=>$item['name']], $rules);
                $errors = $validator -> messages() -> toArray();
                if($errors){
                    return ['type' => 'error', 'msg' => $errors['name']];
                }
            }
            if(isset($item['children'])) {
                $err = $this->checkNames($item['children']) ;
                if(isset($err['type']))
                    return $err;
            }
        }
        return false;
    }

    public function getDelete($area)
    {
        if(!$area || !$area->checkAccess())
            return $this -> redirectIfNotExist();

        if($area->hasChildren()){
            return \Redirect::to('/pods/areas')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }

        else{
            $delete = $area -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/pods/areas')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }
}