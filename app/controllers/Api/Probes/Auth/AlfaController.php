<?php namespace Api\Probes\Auth;

use Api\Probes\MasterController;

class AlfaController extends MasterController
{
    public function auth() //auth & confirm
    {
        $inputs = \Input::json() -> all();
        $rules = [
            'enc_device_id' => 'required',
            'enc_pin'   => 'required',
        ];

        $validator = \Validator::make($inputs, $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) ) {
            $deviceId = $inputs['enc_device_id'];
            $devicePin = $inputs['enc_pin'];

            if ($this->encryption) {
                $mcrypt = new \MCrypt();
                $deviceId = $mcrypt -> decrypt($deviceId);
                $devicePin = $mcrypt -> decrypt($devicePin);
            }

            $probe = \Model\ProbesDevices:: whereDeviceId($deviceId)->first();

            if($probe && $probe -> status == 'create'){
                $probe -> status = 'linked';
                $probe -> active = 1;
                $probe -> update();
            }

            try {
                if (!$probe)
                    throw new \Exception(\Lang::get('/common/messages.device.not_exist'));
                else{
                    if (!$probe -> active)
                        throw new \Exception(\Lang::get('/common/messages.device.not_active'));
                    if ($probe->pin !== $devicePin)
                        throw new \Exception(\Lang::get('/common/messages.auth.not_valid'));
                }
            } catch (\Exception $e) {
                return $this->returnJson(['type' => 'fail', 'msg' => $e->getMessage()]);
            }

            \Navitas::$probe = $probe;
        }
        else {
            return $this->returnJson([
                'type'   => 'error',
                'msg'    => \Lang::get('/common/messages.auth.error'),
                'errors' => $this->ajaxErrors($errors, [])
            ]);
        }
    }
}