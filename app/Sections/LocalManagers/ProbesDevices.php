<?php namespace Sections\LocalManagers;

class ProbesDevices extends LocalManagersSection {


    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('probes/devices', 'Probe Devices');
    }

    public function getIndex()
    {
        $probes = \Model\TemperaturesProbesDevices::where('unit_id','=',$this -> auth_user -> unit() -> id)->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('probes','breadcrumbs'));
    }

    public function getEdit($id)
    {
        $probe = \Model\TemperaturesProbesDevices::find($id);
        if(!$probe || !$probe -> checkAccess())
            return $this->redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('probe','breadcrumbs'));
    }

    public function postEdit($id)
    {
        $probe = \Model\TemperaturesProbesDevices::find($id);
        if(!$probe || !$probe -> checkAccess())
            return $this->redirectIfNotExist();
        $rules = [
            'name'   => 'required',
            'pin'    => 'required'
        ];
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $probe->fill($input);
            $update = $probe->update();

            $type = $update ? 'success' : 'error';
            $msg  = $update ? \Lang::get('/common/messages.update_success') : \Lang::get('/common/messages.update_fail');
            return \Redirect::to('/probes/devices/')->with($type, $msg);
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDeleteDevice($id)
    {
        $probe = \Model\TemperaturesProbesDevices::find($id);
        if(!$probe || !$probe -> checkAccess())
            return $this->redirectIfNotExist();
        $delete = $probe -> delete();

        $type = $delete ? 'success' : 'error';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');

        return \Redirect::back() -> with($type, $msg);
    }

    public function getActivate($id)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();

        $probe = \Model\TemperaturesProbesDevices::find($id);
        if(!$probe || !$probe -> checkAccess())
            return \Response::json(['type' => 'fail', 'msg'  => \Lang::get('/common/messages.not_exist')]);

        $probe -> active = $probe -> active ? 0 : 1;
        $update = $probe -> update();

        $type = $update ? 'success' : 'error';
        $msg  = $update ? \Lang::get('/common/general.'.($probe->active ? 'enabled' : 'disabled' ).'') : \Lang::get('/common/messages.enable_fail');
        $bg_class = 'md-btn md-btn-small md-btn-action md-btn-wave-light waves-effect waves-button waves-light ';
        return \Response::json([
            'type' => $type,
            'msg'  => $msg,
            'data' => [
                'icon' => ($probe -> active ? 'check' : 'close'),
                'title'   => $msg,
                'bg-class'=> ($probe -> active ? $bg_class . 'md-btn-success' : $bg_class . 'md-btn-danger')
            ]
        ]);
    }
}