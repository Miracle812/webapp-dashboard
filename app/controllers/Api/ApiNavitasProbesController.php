<?php

class ApiNavitasProbesController extends BaseController {

    public $restful = true;
    public function __construct (){
        $this->beforeFilter('');
    }

    public function postCreate(){

       $jsonData = \Input::json()->all();

       if (!isset($jsonData['encDevId']) || !isset($jsonData['encCompId']))
           return $this->returnJson (['status'=>'error','message' => 'Insufficient or incorrect data']);

        $encryptedDeviceId   = $jsonData['encDevId'];
        $encryptedComapnyId  = $jsonData['encCompId'];

        $mcrypt = new MCrypt();

        try{
            $decryptedDeviceId   = $mcrypt -> decrypt($encryptedDeviceId);
            $decryptedComapnyId  = $mcrypt -> decrypt($encryptedComapnyId);
        }

        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $isProbeAdded = \Model\TemperaturesProbesDevices::where ('device_id','=',$decryptedDeviceId) -> first();
        if ($isProbeAdded)
        {
            $message = $isProbeAdded -> status == 'create' ? 'The probe was added, waiting for PIN authorization.' : 'The probe is already added and '.($isProbeAdded -> active ? 'is active.' : 'waiting for activating.');
            return $this->returnJson(['status'=>'error','message' => $message]);
        }
        $units = \Model\Units::where ('identifier','=',$decryptedComapnyId) -> first();
        if (!$units)
        {
            return $this->returnJson (['status'=>'error','message'=>'Unknown unit identifier']);
        }

        $createProbe  = new \Model\TemperaturesProbesDevices;
        $createProbe -> name = 'Probe Name';
        $createProbe -> description = 'Probe Description';
        $createProbe -> unit_id = $units -> id;
        $createProbe -> device_id = $decryptedDeviceId;
        $createProbe -> pin = rand(111111, 999999);
        $createProbe -> status = 'create';
        $createProbe -> active = 0;

        try{
            $createProbe -> save();
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $probeId = 'c'.$units -> id.'p'.$createProbe -> id;
        $createProbe -> probe_ident = $probeId;
        $createProbe -> update();
        return $this->returnJson (['status'=>'success','message' => 'Device added, waiting for authorization.','id' => $mcrypt -> encrypt($createProbe -> probe_ident)]);
    }

    public function postConfirmDevice(){
        $jsonData = \Input::json() -> all();

        try{
            if(!isset($jsonData['encProbeId']) || !isset($jsonData['encPin']))
                throw new Exception('Invalid data request.');
        }

        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $encryptedProbeId  = $jsonData['encProbeId'];
        $encryptedPin      = $jsonData['encPin'];
        $encrypter = new MCrypt();

        try{
            $decryptedProbeId  = $encrypter -> decrypt($encryptedProbeId);
            $decryptedPin      = $encrypter -> decrypt($encryptedPin);
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }
        try{
            $device = \Model\TemperaturesProbesDevices::where('probe_ident','=',$decryptedProbeId)->first();
            if(!$device)
                throw new Exception('No data for this id probe.');
        }

        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        try{
            if($device -> pin !== $decryptedPin)
                throw new Exception('Invalid auth.');
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $device -> status = 'linked';
        $device -> active = 1;
        $device -> update();
        return $this->returnJson (['status'=>'success','message' => 'Device Linked Successful']);
    }

    public function getMenu(){
        $device = \Navitas::$probe;
        $foodRow = \Model\TemperaturesProbesMenuItems::where('unit_id','=',$device->unit_id)->first();
        $foodRow = $foodRow ? unserialize($foodRow -> structure) : [];
        return $this->returnJson (['status'=>'success','result' => $foodRow]);
    }

    public function getStaff(){
        $device = \Navitas::$probe;
        $staff = \Model\Staffs::select('id','first_name', 'surname','avatar')->where('unit_id','=',$device->unit_id)->whereSmartprobe(1)->get();
        $out = [];
        if(count($staff))
            foreach($staff as $staf){
                $out[] = ['id' => $staf->id, 'name' => $staf -> fullname(), 'avatar' => $staf -> avatar];
            }
        return $this->returnJson (['status'=>'success','result' => $out]);
    }

    public function getSuppliers(){
        $device = \Navitas::$probe;
        $suppliers = \Model\Suppliers::select('id','name','logo')->where('unit_id','=',$device->unit_id)->get()->toArray();
        $suppliers = $suppliers ? : [];
        return $this->returnJson (['status'=>'success','result' => $suppliers]);
    }

    public function getUnit()
    {
        $device = \Navitas::$probe;
        $unitId = $device->unit_id;
        $unit = \Model\Units::select('name', 'email', 'phone', 'post_code' , 'city', 'street_number', 'logo')->find($device->unit_id)->toArray();
        $response  = $unit ? : [];
        $response['compliance'] = $this->probesAreas($unitId);
        return $this->returnJson (['status'=>'success','result' => [$response]]);
    }

    public function probesAreas($unitId)
    {
        $data  = [];
        $areas = \Model\TemperaturesProbesAreas::whereUnitId($unitId)->get();
        if($areas->count())
        {
            foreach ($areas as $area)
            {
                $data[] = [
                    "service_area"     => $area->parent_id,
                    "name"             => $area->name,
                    "description"      => $area->description,
                    "rule_description" => $area->rule_description,
                    "warning_min"      => $area->warning_min,
                    "warning_max"      => $area->warning_max,
                    "valid_min"        => $area->valid_min,
                    "valid_max"        => $area->valid_max,
                ];
            }
        }
        return $data;
    }

    public function getSupplierProducts($supplier_id){
        $device = \Navitas::$probe;
        $supplier = \Model\Suppliers::where('unit_id','=',$device -> unit_id)->where('id','=',$supplier_id)->first();
        $products = $supplier -> products ? unserialize($supplier -> products) : null;
        if (!$products)
            return $this->returnJson(['status'=>'error','message' => 'No products']);
        $productsList = \Model\ProductsList::select('id','name')->whereIn('id',array_values($products))->get()->toArray();
        $productsList = $productsList ? : [];
        return $this->returnJson(['status'=>'success','result' => $productsList]);
    }

    public function getStartedGoodsIn(){
        $device = \Navitas::$probe;
        $goodsIn = \Model\TemperaturesForGoodsIn::select('id','device_identifier','item_name','staff_name','temperature','date_time')->whereUnitId($device -> unit_id)->whereStatus(1)->wherePairId(0)->get()->toArray();
        if (!$goodsIn)
            return $this->returnJson(['status'=>'error','message' => 'No records']);
        return $this->returnJson(['status'=>'success','result' => $goodsIn]);
    }

    public function postServiceTemperature(){

        $device = \Navitas::$probe;
        $unitId = $device->unit_id;

        try{
            $rules = [
                'datetime'=>'required',
                'a_id'  => 'required',
                'u_id'  => 'required',
                'f_id' => 'required',
                't_val' => 'required'];
            $input = \Input::all();
            $validator = \Validator::make($input, $rules);
            if ($validator -> fails()) {
                \Log::error(['smartprobes-temperatures-service'=>$input]);
                throw new Exception('Insufficient or incorrect data.');
            }
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $area = \Model\TemperaturesProbesAreas::whereId($input['a_id'])->first();

        if(!$area || !$area->group || $area -> group != 'probes')
            try{
                if(!$area || !$area->group || $area -> group -> identifier != 'probes')
                    throw new Exception('Incorrect data for area or group is not valid.');
            }
            catch(\Exception $e){
                return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
            }

        $area = $area -> getTargetArea($unitId);

        $dangerMin  = $area->warning_min;
        $dangerMax  = $area->warning_max;
        $warningMin = $area->valid_min;
        $warningMax = $area->valid_max;

//menuItem
        $menuItem = \Model\TemperaturesProbesMenuItems::whereUnitId($unitId)->first();
        $itemName = $menuItem->getItemByKey(null, $input['f_id']);
//Staff
        $staff = \Model\Staffs::find($input['u_id']);
        $staffName = $staff ? $staff -> fullname() : 'N/A';
//Rules
        $rule = \Model\TemperaturesLogRules::firstOrCreate(['valid_min'=>$warningMin,'valid_max'=>$warningMax,'warning_min'=>$dangerMin,'warning_max'=>$dangerMax]);
//Status
        $status = isset($input['status'])?$input['status']:0;
//Create
        $create    = new \Model\TemperaturesForProbes();
        $create -> unit_id      = $unitId;
        $create -> area_id      = $area -> id;
        $create -> rules_id     = $rule -> id;
        $create -> invalid_id   = 0;
        $create -> pair_id      = 0; 
        $create -> device_identifier = $device -> device_id;
        $create -> device_name  = $device -> name;
        $create -> item_name    = $itemName;
        $create -> item_id      = $input['f_id'];
        $create -> staff_name   = $staffName;
        $create -> temperature  = $input['t_val'];
        $create -> status       = $status;
        $save    = $create -> save();

        if($save){
            if($create -> status == 2){
                $this->updateFirstTemperature($create);
            }
            if($create -> status !== 1){
                $tempService = new \Services\Temperatures();
                $tempService -> commonVerifier($area,$create);
            }
            
            return $this->returnJson (['status'=>'success']);
        }
        else
            return $this->returnJson (['status'=>'error']);
    }

    public function postMonitoredGoodsIn()
    {
        $device = \Navitas::$probe;
        try{
            $rules = [
                'datetime'=>'required',
                's_id'  => 'required',
                'u_id'  => 'required',
                'p_arr' => 'required',
                't_val' => 'required',
                //'i_val' => 'required',
                //'j_val' => 'required',
                'p_val' => 'required',
                'd_val' => 'required'];
            $input = \Input::all();
            $validator = \Validator::make($input, $rules);
            if ($validator -> fails()) {
                \Log::error(['smartprobes-goods-in'=>$input]);
                throw new Exception('Insufficient or incorrect data.');
            }
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

//products names
        $products = \Model\ProductsList::whereIn('id',$input['p_arr'])->lists('name');
        $productsNames = implode(', ', $products);
//Staff name
        $staff = \Model\Staffs::find($input['u_id']);
        $staffName = $staff ? $staff -> fullname() : 'N/A';
//Supplier name
        $supplier = \Model\Suppliers::find($input['s_id']);
        $supplierName = $supplier ? $supplier -> name : 'N/A';

        $goodsIn = new \Model\TemperaturesForGoodsIn();

        $goodsIn -> supplier_id     = $input['s_id'];
        $goodsIn -> supplier_name   = $supplierName;
        $goodsIn -> staff_id        = $staff -> id;
        $goodsIn -> staff_name      = $staffName;
        $goodsIn -> products_name   = $productsNames;
        $goodsIn -> device_id       = $device -> probe_ident;
        $goodsIn -> device_name     = $device -> name;

        $goodsIn -> temperature     = $input['t_val'];
        $goodsIn -> invoice_number  = $input['i_val'];
        $goodsIn -> job_number      = isset($input['j_val']) ? $input['j_val'] : NULL;
        $goodsIn -> package_accept  = $input['p_val'];
        $goodsIn -> date_code_valid = $input['d_val'];
        $goodsIn -> unit_id         = $device -> unit_id;
        $goodsIn -> date_time       = $input['datetime'];
        $goodsIn -> compliant       = !$goodsIn -> package_accept || !$goodsIn -> date_code_valid ? 0 : 1;

        $save = $goodsIn -> save();

        if($save){
            $tempService = new \Services\Temperatures();
            $tempService -> commonVerifier($goodsIn->supplier,$goodsIn);
        }

        if($save)
            return $this->returnJson (['status'=>'success']);
        else
            return $this->returnJson (['status'=>'error', 'message' => '']);
    }

    public function postCalibration(){
        $jsonData = \Input::json() -> all();
        try{
            if( !isset($jsonData['temp_value']))
                throw new Exception('Insufficient or incorrect data.');
        }
        catch(\Exception $e){
            return $this->returnJson (['status'=>'error','message' => $e->getMessage()]);
        }

        $device = \Navitas::$probe;
        $calibrate = new \Model\TemperaturesForCalibration();
        $calibrate -> unit_id           = $device->unit_id;
        $calibrate -> temperature       = $jsonData['temp_value'];
        $calibrate -> device_name       = $device->name;
        $calibrate -> device_identifier = $device->device_id;
        $save = $calibrate -> save();
        if($save)
            return $this->returnJson (['status'=>'success']);
        else
            return $this->returnJson (['status'=>'error', 'message' => '']);
    }

    public function updateFirstTemperature($item){
        $first = \Model\TemperaturesForProbes::
            whereRaw('id IN (select max(id) from temperatures_for_probes where status = 1 and item_id = '.$item->item_id.')')->
            where('created_at','<=',$item-> created_at) ->
            where('area_id', '=', $item  -> area_id)    ->
            where('unit_id', '=', $item  -> unit_id)    ->
            first();
        if($first && $item){
            $first -> pair_id = $item -> id;
            $first -> update();
            $item -> pair_id = $first -> id;
            $item -> update();
            return true;
        }
        return false;
    }

    public function returnJson($data,$code = 200){
        $response = \Response::json($data,$code,[],JSON_NUMERIC_CHECK);
        $response->header('Cache-control', 'public, max-age=900');
        //Log::info($response);
        return $response;
    }
}