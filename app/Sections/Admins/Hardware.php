<?php namespace Sections\Admins;

class Hardware extends AdminsSection {

    public function __construct(\Model\Headquarters $headquarters)
    {
        $this -> headquarters = $headquarters;
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('hardware', 'Hardware');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('List',false) );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable($type)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $clientId = \Session::get('selected-client');
        $siteId = \Session::get('selected-site');
        if (is_numeric($siteId)) {
            $sites = \Model\Units::where('id',$siteId)->get();
        }
        else{
            if(is_numeric($clientId)) {
                $client = \Model\Headquarters::find($clientId);
                $sites = $client->units;
            }
            else{
                $sites = \Model\Units::where('active',1)->get();
            }
        }
        switch ($type){
            case 'probes' :
                $hardwares = \Model\TemperaturesProbesDevices::select(['*','device_id as identifier'])->whereIn('unit_id',$sites->lists('id'))->get();
                break;
            case 'pods' :
                $hardwares = \Model\TemperaturesPodsSensors::whereIn('temperatures_for_pods.unit_id',$sites->lists('id'))->join('temperatures_for_pods', function($join)
                {
                    $join->on('temperatures_pods_sensors.id', '=', 'temperatures_for_pods.pod_id');
                })->whereRaw('temperatures_for_pods.id IN (select max(id) from temperatures_for_pods where temperatures_for_pods.pod_id = temperatures_pods_sensors.id)')->get();
                break;
            case 'tablets' :
                $hardwares = null;
                break;
            default :
                $hardwares = \Model\TemperaturesPodsSensors::whereIn('temperatures_for_pods.unit_id',$sites->lists('id'))->join('temperatures_for_pods', function($join)
                {
                    $join->on('temperatures_pods_sensors.id', '=', 'temperatures_for_pods.pod_id');
                })->whereRaw('temperatures_for_pods.id IN (select max(id) from temperatures_for_pods where temperatures_for_pods.pod_id = temperatures_pods_sensors.id)')->get();
                $hardwares = $hardwares -> merge(\Model\TemperaturesProbesDevices::select(['*','device_id as identifier'])->whereIn('unit_id',$sites->lists('id'))->get());
                break;
        }

        $options = [];
        if (count($hardwares)){
            foreach ($hardwares as $hardware)
            {
                $btv = $hardware->battery_voltage ? : 'N/A';
                $btl = $hardware->battery_level ? : 'N/A';
                $options[] = [
                    '',
                    $hardware->name,
                    ($hardware->unit && ($headquarter = $hardware->unit->headquarter)) ? $headquarter->name : 'N/A',
                    $hardware->unit ? $hardware->unit->name : 'N/A',
                    $hardware->identifier,
                    $btv.' / '.$btl,
                    'N/A',
                    $hardware->temperature ? : 'N/A'

                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getSites($id)
    {
        \Session::put('selected-client',$id);
        $sites = \Model\Units::whereActive(1)->whereHas('headquarter',function($query) use($id){
            $query->whereId($id);
        })->get()->lists('name','id');
        return \Form::select('site',(['all'=>'All Sites']+$sites), \Session::get('selected-site'), ['class'=>'form-control']);
    }

    public function postClient()
    {
        \Session::put('selected-client',\Input::get('client'));
        \Session::put('selected-site',\Input::get('site'));
        return \Redirect::back();
    }













    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function getEdit($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();
        $threadOpt = \Model\OptionsMenu::whereIdentifier($unit->getTable())->first();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','unit','threadOpt'));
    }

    public function getManageUnitModules($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        $role = \Role::
            //with('perms')
            with([ 'perms' => function($query) {
                $query->whereDisabled(0);
            }])
            ->find(3);

        $modules = $hq->getDisabledModules();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('manage-unit-modules') );
        return \View::make($this->regView('manage-unit-modules'), compact('breadcrumbs','hq','role','modules'));
    }

    public function postManageUnitModules($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        \OptionsRepository::create($hq,'manage_unit_modules',['disabled_modules'=>\Input::get('modules')]);

        return \Redirect::back()->withSuccess('Headquarter modules list - submitted.');
    }

    public function getDeleteModulesList($id)
    {
        $hq = \Model\Headquarters::find($id);
        if(!$hq || !$hq->checkAccess())
            return $this -> redirectIfNotExist();

        $options = $hq->getOption('manage_unit_modules');
        if($options)
            $options -> delete();
        return \Redirect::back()->withSuccess('List removed');
    }

    public function getEditLogo($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit-logo') );
        return \View::make($this->regView('modal.edit-logo'), compact('breadcrumbs','unit'))->render();
    }

    public function postEditLogo($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $postData = \Input::get();
        $photo    = new \Services\FilesUploader('headquarters');
        $path     = $photo -> getUploadPath($unit -> id);
        $logo     = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$unit -> logo);
        $unit -> logo = $logo;
        $update = $unit -> update();
        if($update)
            return \Response::json(['type'=>'success', 'msg' => \Lang::get('/common/messages.update_success'), 'url' => $unit -> logo]);
        else
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.update_fail')]);
    }

    public function postCreate()
    {
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input     = \Input::all();
        $new       = new \Model\Headquarters();
        $rules     = $new -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/headquarters')->with($type, \Lang::get('/common/messages.create_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postEdit($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);
        $input     = \Input::all();
        $rules     = $unit -> rules;
        $rules['email'] = 'required|email|unique:units,email,'.$unit -> id;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $unit -> fill($input);
            $update = $unit -> update();
            \OptionsRepository::createByInputs($unit,$input);
            $type = $update ? 'success' : 'fail';
            return \Redirect::back()->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getActive($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();

        $unit -> active = $unit -> active ? 0 : 1;
        $update = $unit -> update();
        $type = $update ? 'success' : 'fail';

        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    }

    public function getDelete($id)
    {
        $unit = \Model\Headquarters::find($id);
        if(!$unit || !$unit->checkAccess())
            return $this -> redirectIfNotExist();

        $delete = $unit -> delete();
        $type   = $delete ? 'success' : 'fail';
        return \Redirect::to('/headquarters')->with($type, \Lang::get('/common/messages.delete_'.$type));
    }

    public function getUnits($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $headquarter = \Model\Headquarters::find($id);
        if(!$headquarter)
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);

        $units = $headquarter -> units ->lists('name','id');
        $units = count($units) ? $units : ['error'=>\Lang::get('/common/messages.not_exist')];
        return \View::make($this->regView('partials.units'), compact('units'))->render();
    }
}