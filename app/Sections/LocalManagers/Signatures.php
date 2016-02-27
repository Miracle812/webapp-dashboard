<?php namespace Sections\LocalManagers;

class Signatures extends LocalManagersSection {

    protected $user;
    protected $signature;
    protected $signService;

    public function __construct(\Services\Signature $signService, \Model\Signatures $signature){
        parent::__construct();
        $this -> signService    = $signService;
        $this -> signature      = $signature;
        $this -> user           = \Auth::user();
        $this -> breadcrumbs -> addCrumb('signatures', 'Signatures');
    }

    public function getAuthorize()
    {
        $signService = $this -> signService;
        $this->params = ['signature' => $signService];
        if ($signService->isSignAndRemember())
        {
            $signService->forgetSignAuth();
            $this -> view = 'confirmation';
            $this -> params = [
                'data' => [\Lang::get('Signature Authorization has been disabled'),\Lang::get('Signature Authorization has been disabled')],
            ];
        }
        else
        {
            $this->view = $signService->isSignature() ? 'authorization' : 'create_edit';
        }

        return $this->showView();
        return \View::make($this->regView('list'), compact('breadcrumbs'));
    }

    public function postAuthorize()
    {
        parse_str(\Input::get('auth-data'), $data);
        $pin = $data['pin_number'];

        if ($pin && $this->signService->isValidPin($pin) )
        {
            $this->signService->rememberSign();
            $data = array('type'=>'success', 'msg'=>\Lang::get('Authorization Successful'));
        }
        else
            $data = array('type'=>'danger', 'msg'=>\Lang::get('Authorization Unsuccessful'));

        return \Response::json($data, 200);
    }

    public function postCreate()
    {
        parse_str(\Input::get('data'), $formData);
        $isSign = $this -> signService -> isSignature() ? true : false;
        $sign = $isSign ? $this -> signService -> getUserSignature() : $this -> signature;
        $sign -> user_id    = $this -> user -> id;
        $sign -> unit_id = $this -> user -> unit() -> id;
        $sign -> signature  = \Input::get('signature');

        if (isset($formData['pin_number']) && $formData['pin_number'])
            $sign -> pin = $formData['pin_number'];

        $isSign ? $sign->update() : $sign->save();
        $this -> user -> signature_id = $sign -> id;
        $this -> signService -> rememberSign();
        $data = array('type'=>'success', 'msg'=>\Lang::get('Update Successful'));

        return \Response::json($data, 200);
    }

    public function getDisable()
    {
        $this->signService->forgetSignAuth();
        return \Redirect::back()->with('message',\Lang::get('Disabled Successful'));
    }
}
