<?php namespace Api\Probes\Base;

use Api\Probes\MasterController;

class AlfaController extends MasterController
{

    public function postIndex()
    {
        return $this->returnJson (['type'=>'fail','msg' => \Lang::get('/common/messages.route_404')]);
    }
    public function getIndex()
    {
        return $this->returnJson (['type'=>'fail','msg' => \Lang::get('/common/messages.route_404')]);
    }

    public function postCreate()
    {
        $inputs = \Input::json() -> all();
        $rules = [
            'enc_device_id' => 'required',
            'enc_unit_id'   => 'required',
        ];

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $deviceId = $inputs['enc_device_id'];
            $unitIden = $inputs['enc_unit_id'];

            if ($this->encryption) {
                $mcrypt = new \MCrypt();
                $deviceId = $mcrypt -> decrypt($deviceId);
                $unitIden = $mcrypt -> decrypt($unitIden);
            }

            $unit     = \Model\Units :: whereIdentifier ($unitIden) -> first();
            $probe    = \Model\ProbesDevices :: whereDeviceId ($deviceId) -> first();

            try{
                if(!$unit){
                    throw new \Exception(\Lang::get('/common/messages.unit.not_exist'));
                }

                if($probe)
                    throw new \Exception(\Lang::get('/common/messages.device.already_exist'));
            }
            catch(\Exception $e){
                return $this->returnJson (['type'=>'fail','msg' => $e->getMessage()]);
            }

            $new  = new \Model\ProbesDevices;
            $new -> name        = 'Probe-'.$deviceId;
            $new -> unit_id     = $unit -> id;
            $new -> device_id   = $deviceId;
            $new -> pin         = rand(111111, 999999);
            $new -> status      = 'create';
            $new -> active      = 0;
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return $this->returnJson (['type'=>$type,'msg' => \Lang::get('/common/messages.create_'.$type)]);
        }
        else{
            return $this->returnJson(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_error'), 'errors' => $this -> ajaxErrors($errors,[])]);
        }
    }

    public function postGetMenu(){
        $device = \Navitas::$probe;
        $foodRow = \Model\TemperaturesMenuItems::where('unit_id','=',$device->unit_id)->first();
        $foodRow = $foodRow ? unserialize($foodRow -> structure) : [];
        return $this->returnJson (['type'=>'success','data' => $foodRow]);
    }

    public function postGetStaff(){
        $device = \Navitas::$probe;
        $staff = \Model\Staffs::select('id','first_name', 'surname','avatar')->where('unit_id','=',$device->unit_id)->whereSmartprobe(1)->get();
        $out = [];
        if(count($staff))
            foreach($staff as $staf){
                $out[] = ['id' => $staf->id, 'name' => $staf -> fullname(), 'avatar' => $staf -> avatar];
            }
        return $this->returnJson (['type'=>'success','data' => $out]);
    }

    public function postGetSuppliers(){
        $device = \Navitas::$probe;
        $suppliers = \Model\Suppliers::select('id','name','logo')->where('unit_id','=',$device->unit_id)->get();
        $suppliers = $suppliers ? : [];
        return $this->returnJson (['type'=>'success','data' => $suppliers]);
    }

    public function postGetUnit(){
        $device = \Navitas::$probe;
        $unit = \Model\Units::select('name', 'email', 'phone', 'post_code' , 'city', 'street_number', 'logo')->where('id','=',$device->unit_id)->get();
        $unit = $unit ? : [];
        return $this->returnJson (['type'=>'success','data' => $unit]);
    }

    public function postGetStartedChillings(){
        $device = \Navitas::$probe;
        $data = \Model\TemperaturesForProbes::
        select('id','device_name','item_name','staff_name','temperature','date_time') ->
        whereStatus(1) ->
        where('unit_id','=',$device->unit_id) -> get();
        return $this->returnJson (['type'=>'success','data' => $data]);
    }

    public function postGetSupplierProducts($id)
    {
        $supplier = \Model\Suppliers::find($id);
        try{
            if(!$supplier) {
                throw new \Exception(\Lang::get('/common/messages.supplier.not_exist'));
            }
            else {
                $products =  unserialize($supplier->products);
                if (!$products)
                    throw new \Exception(\Lang::get('/common/messages.products.not_exist'));
            }
        }
        catch(\Exception $e){
            return $this->returnJson (['type'=>'fail','msg' => $e->getMessage()]);
        }
        $products = \Model\ProductsList::select('id','name')->whereIn('id',$products)->get();
        return $this->returnJson(['type'=>'success','data' => $products]);
    }

    public function postTemperature(){
        $type = 'error';
        $device = \Navitas::$probe;
        $unitId = $device -> unit_id;
        $inputs = \Input::json() -> all();

        $rules = [
            'area_id'    => 'required',
            'date_time'  => 'required',
            'staff_id'   => 'required',
            'menu_id'    => 'required',
            'temperature'=> 'required',
        ];

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            $area  = \Model\TemperaturesProbesAreas::whereId($inputs['area_id'])->first();
            $staff = \Model\Staffs::find($inputs['staff_id']);
            $menu  = \Model\TemperaturesMenuItems::whereUnitId($unitId)->first();
            try{
                if(!$area)
                    throw new \Exception(\Lang::get('/common/messages.area.not_exist'));
                else{
                    if(!$area->group || $area -> group -> identifier !== 'probes')
                        throw new \Exception(\Lang::get('/common/messages.group.not_exist'));
                }
                if(!$staff)
                    throw new \Exception(\Lang::get('/common/messages.staff.not_exist'));
                if(!$menu)
                    throw new \Exception(\Lang::get('/common/messages.menu.not_exist'));
            }
            catch(\Exception $e){
                return $this->returnJson (['type'=>'fail','msg' => $e->getMessage()]);
            }

            $area = $area -> getTargetArea($unitId);
            $itemName = $menu->getItemByKey(null, $inputs['menu_id']);
            $status = isset($jsonData['chilling_status']) ? $jsonData['chilling_status'] : 0;

            $create    = new \Model\TemperaturesForProbes();
            $create -> unit_id      = $unitId;
            $create -> area_id      = $area -> id;
            $create -> invalid_id   = 0;
            $create -> pair_id      = 0;
            $create -> device_identifier = $device -> device_id;
            $create -> device_name  = $device -> name;
            $create -> date_time    = $inputs['date_time'];
            $create -> item_name    = $itemName;
            $create -> item_id      = $inputs['menu_id'];
            $create -> staff_name   = $staff -> fullname();
            $create -> temperature  = $inputs['temperature'];
            $create -> status       = $status;
            $save    = $create -> save();

            if($save){
                if($create -> status == 2){
                    $this->updateFirstTemperature($create);
                }
                $tempService = new \Services\Temperatures();
                $tempService -> commonVerifier($area,$create);
            }
            $type = $save ? 'success' : 'fail';
            return $this->returnJson (['type'=>$type,'msg' => \Lang::get('/common/messages.create_'.$type)]);
        }
        else
        {
            return $this->returnJson(['type'=>'error','msg'=>\Lang::get('/common/messages.create_error'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function postGoodsIn()
    {
        $device = \Navitas::$probe;
        $inputs = \Input::json() -> all();

        $rules = [
            'date_time'      => 'required',
            'supplier_id'    => 'required',
            'staff_id'       => 'required',
            'product_ids'    => 'required',
            'temperature'    => 'required',
            'invoice_number' => 'required',
            'package_accept' => 'required',
            'date_code_valid'=> 'required',
        ];

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();

        if(!$errors)
        {
            $products = \Model\ProductsList::whereIn('id',$inputs['product_ids'])->lists('name');
            $staff    = \Model\Staffs::find($inputs['staff_id']);
            $supplier = \Model\Suppliers::find($inputs['supplier_id']);

            try{
                if(!$products)
                    throw new \Exception(\Lang::get('/common/messages.products.not_exist'));
                if(!$staff)
                    throw new \Exception(\Lang::get('/common/messages.staff.not_exist'));
                if(!$supplier)
                    throw new \Exception(\Lang::get('/common/messages.supplier.not_exist'));
            }
            catch(\Exception $e){
                return $this->returnJson (['type'=>'fail','msg' => $e->getMessage()]);
            }

            $goodsIn  = new \Model\TemperaturesForGoodsIn();
            $goodsIn -> fill($inputs);
            $goodsIn -> unit_id       = $device -> unit_id;
            $goodsIn -> device_id     = $device -> id;
            $goodsIn -> device_name   = $device -> name;
            $goodsIn -> staff_name    = $staff -> fullname();
            $goodsIn -> supplier_name = $supplier -> name;
            $goodsIn -> products_name = implode(', ', $products);
            $goodsIn -> compliant     = !$goodsIn -> package_accept || !$goodsIn -> date_code_valid ? 0 : 1;
            $save = $goodsIn -> save();

            if($save){
                $tempService = new \Services\Temperatures();
                $tempService -> commonVerifier($goodsIn->supplier,$goodsIn);
            }

            $type = $save ? 'success' : 'fail';
            return $this->returnJson (['type'=>$type,'msg' => \Lang::get('/common/messages.create_'.$type)]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_error'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function postCalibration()
    {
        $inputs = \Input::json() -> all();
        $rules = [
            'temperature'    => 'required',
        ];
        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(!$errors)
        {
            $device = \Navitas::$probe;
            $calibrate = new \Model\TemperaturesForCalibration();
            $calibrate -> temperature       = $inputs['temperature'];
            $calibrate -> unit_id           = $device->unit_id;
            $calibrate -> device_name       = $device->name;
            $calibrate -> device_identifier = $device->device_id;
            $save = $calibrate -> save();
            $type = $save ? 'success' : 'fail';
            return $this->returnJson (['type'=>$type,'msg' => \Lang::get('/common/messages.create_'.$type)]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_error'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
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
}