<?php namespace Sections\LocalManagers;

//use \Websocket\Pods\NaviSockets\ZmqConns as ZmqConn;

class PodsSensors extends LocalManagersSection
{
    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('pods/sensors', 'Pod Sensors');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('probes','breadcrumbs'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function getEdit($id)
    {
        $pod  = \Model\TemperaturesPodsSensors::find($id);
        if(!$pod || !$pod -> checkAccess())
            return $this -> redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('pod','breadcrumbs'));
    }
/*
    public function getStatus($id)
    {
        $pod  = \Model\TemperaturesPodsSensors::find($id);
        if(!$pod || !$pod -> checkAccess())
            return $this -> redirectIfNotExist();
        $conn = new ZmqConn();
        $socket = $conn -> checkServer();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('status') );
        return \View::make($this->regView('status'), compact('pod','socket','breadcrumbs'));
    }
*/
    public function postCreate()
    {
        $pod  = new \Model\TemperaturesPodsSensors();
        $rules = $pod->rules;
        $rules['identifier'] = 'required|AlphaNum|between:5,30|unique:temperatures_pods_sensors,identifier';
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $pod -> fill($input);
            $pod -> unit_id   = $this->auth_user->unit()->id;
            $save = $pod -> save();

            if($areaId = $input['area_id']) {
                $new = new \Model\AssignedPodsSensors();
                $new->insert(['pod_sensor_id'=>$pod->id, 'pod_area_id'=>$areaId]);
            }
            $type = $save ? 'success' : 'fail';
            $msg  =\Lang::get('/common/messages.create_'.$type);
            return \Redirect::to('/pods/sensors/')->with($type, $msg);
        }
        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postEdit($id)
    {
        $pod  = \Model\TemperaturesPodsSensors::find($id);
        if(!$pod || !$pod -> checkAccess())
            return $this -> redirectIfNotExist();
        $rules = $pod->rules;
        $rules['identifier'] = 'required|AlphaNum|between:5,30|unique:temperatures_pods_sensors,identifier,'.$pod->id;
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $pod -> fill($input);
            \Model\AssignedPodsSensors::whereIn('pod_sensor_id',[$id])->delete();
            if($areaId = $input['area_id']) {
                $new = new \Model\AssignedPodsSensors();
                $new->insert(['pod_sensor_id'=>$id, 'pod_area_id'=>$areaId]);
            }

            $update = $pod -> update();
            $type = $update ? 'success' : 'fail';
            $msg  =\Lang::get('/common/messages.update_'.$type);
            return \Redirect::to('/pods/sensors/')->with($type, $msg);
        }
        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }
/*
    public function getSetAlarm($id)
    {
        $pod = \Model\TemperaturesPodsSensors::find($id);
        if(!$pod || !$pod -> checkAccess())
            return $this -> redirectIfNotExist();
        $hub = $pod -> getHub();
        if(!$hub || !$hub -> resource_id || !$hub -> ip)
            return $this -> redirectIfNotExist();

        $area = $pod -> area;

        $conn = new ZmqConn();
        if(!$conn -> connection)
            return \Redirect::back();

        $data = json_encode([
            'socket' => [
                'id' => $hub -> resource_id,
                'ip' => $hub -> ip],
            'data'   => [
                'cmd'  => '0069',
                'warning_min' => $area -> warning_min,
                'warning_max' => $area -> warning_max,
                'valid_min' => $area -> valid_min,
                'valid_max' => $area -> valid_max,
                'pod_id' => $pod -> identifier,
                'hub_id' => $hub -> identifier],
        ]);
        $send = $conn -> sendMessage($data);
        $conn -> disconnect();
        return \Redirect::back();
    }

    public function postRequest($cmd, $type, $id)
    {

        $rules = $niceNames = [];
        if(in_array($cmd, ['60','61']))
        {
            $key_from = 'date_from.' . $cmd  ;
            $key_to   = 'date_to.' . $cmd ;
            $rules[$key_from] = 'required|Date';
            $rules[$key_to]   = 'required|Date';
            $niceNames = [
                $key_from => 'Date from',
                $key_to   => 'Date to',
            ];

        }
        if(in_array($cmd, ['69','6A']))
        {
            $key_min = 'alert_min.' . $cmd ;
            $key_max = 'alert_max.' . $cmd ;
            $rules[$key_min] = 'required|numeric|min:-100|max:100';
            $rules[$key_max] = 'required|numeric|min:-100|max:100';
            $niceNames = [
                $key_min => 'Alert minimum',
                $key_max => 'Alert maximum',
            ];
        }

        $input = \Input::all();

        $validator = \Validator::make($input, $rules);
        $validator->setAttributeNames($niceNames);
        if(!$validator -> fails())
        {
            $cmdData = $commonData = $typeData = [];
            if($type == 'pod'){
                $pod = \Model\PodsDevices::find($id);
                if(!$pod || !$pod -> checkAccess())
                    return $this -> redirectIfNotExist();
                $area = $pod -> area;
                $hub = $pod -> getHub();

                $typeData = [
                    'cmd'  => '00'.$cmd,
                    'pod_id' => $pod -> identifier,
                    'hub_id' => $hub -> identifier,
                ];
            }
            else{
                $pod_identifier = 'FFFFFF';
                $area = null;
                $hub = \Model\HubsDevices::find($id);

                $typeData = [
                    'cmd'  => '00'.$cmd,
                    'pod_id' => $pod_identifier,
                    'hub_id' => $hub -> identifier,
                ];
            }

            if(!$hub || !$hub -> resource_id || !$hub -> ip)
                return $this -> redirectIfNotExist();

            $commonData = [
                'socket' => [
                    'id' => $hub -> resource_id,
                    'ip' => $hub -> ip
                ]
            ];

            if(in_array($cmd, ['60','61']))
            {
                $cmdData = [
                    'cmd'  => '00'.$cmd,
                    'date_from' => \Carbon::createFromFormat('Y-m-d H:i', \Input::get($key_from)),
                    'date_to' => \Carbon::createFromFormat('Y-m-d H:i', \Input::get($key_to)),
                ];
            }
            elseif(in_array($cmd, ['69','6A']))
            {
                $cmdData = [
                    'cmd'  => '00'.$cmd,
                    'alert_min' => \Input::get($key_min),
                    'alert_max' => \Input::get($key_max),
                ];
            }

            $data = $commonData + ['data' => $typeData + $cmdData];

            $conn = new ZmqConn();
            if(!$conn -> connection)
                return \Redirect::back()->withErrors(['']);

            $jsonData = json_encode($data);
            $conn -> sendMessage($jsonData);
            $conn -> disconnect();
            return \Redirect::back();
        }
        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }

    }
*/
    public function getDatatable()
    {
        $items = \Model\TemperaturesPodsSensors::where('unit_id','=',$this->auth_user->unitId())->get();
        $options = [];
        $items = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $items) : $items -> take(100);
        foreach($items as $item){
            if(!$item || !$item -> checkAccess())
                continue;

            $options[] = [
                strtotime($item -> created_at),
                $item -> created_at(),
                $item -> name,
                $item -> identifier,
                ($area = $item -> area()) ? ucfirst($area -> group) . ' / ' . implode($area -> getParentsNames(), ' / ') . ' / ' . $area -> name : '<span class="uk-text-danger bold">[Not Assigned]</span>',
                //\HTML::mdOwnOuterBuilder(\HTML::mdOwnButton($item->id,'status','search', 'md-btn-default')),
                \HTML::mdOwnOuterBuilder(
                    \HTML::mdActionButton($item->id, 'pods/sensors', 'edit', 'edit', \Lang::get('/common/general.edit')).' '.
                    \HTML::mdActionButton($item->id, 'pods/sensors', 'delete', 'clear', \Lang::get('/common/general.delete'))
                )
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getLoadAreas($id)
    {
        $html = '';
        $unitId = $this->auth_user->unitId();
        $areas = \Model\TemperaturesPodsAreas::whereUnitId($unitId)->whereType('area')->orderBy('parent_id')->get();

        if($areas){
            $data[0]='[Not Assigned]';
            foreach($areas as $area){
                $data[$area->id] = $area->name . ($area->group == 'pods'? ', [ Group: '.implode($area->getParentsNames(), ' / ') . ' ]' : '');
            }
            $html =  \Form::select('area_id', $data, $id, ['data-md-selectize'=>'']);
        }
        else
            $html = '<span class="text-danger m-r">'.\Lang::get('/common/messages.empty_data').'</span><a class="font-bold text-primary" href="/pods/areas">'.\Lang::get('/common/general.add').'</a>';
        return $html;
    }

    public function getDelete($id)
    {
        $area = \Model\TemperaturesPodsSensors::find($id);
        if(!$area || !$area -> checkAccess())
            return $this -> redirectIfNotExist();

        $delete = $area -> delete();

        $type = $delete ? 'success' : 'fail';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');

        return \Redirect::back()->with($type, $msg);
    }
}