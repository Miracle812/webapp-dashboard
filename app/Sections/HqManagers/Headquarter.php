<?php namespace Sections\HqManagers;

class Headquarter extends HqManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> headquarter = $this -> auth_user -> headquarter();
        $this -> breadcrumbs -> addCrumb('headquarter', 'Headquarter');
    }

    public function getEdit()
    {
        $headquarter = $this -> headquarter;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('modal.edit'), compact('breadcrumbs', 'headquarter'));
    }

    public function getEditLogo()
    {
        $headquarter = $this -> headquarter;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('modal.edit-logo'), compact('breadcrumbs', 'headquarter'));
    }

    public function postEdit()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();

        $headquarter = $this -> headquarter;
        $input     = \Input::all();
        $rules     = $headquarter -> rules;
        $rules['email'] = 'required|email|unique:headquarters,email,'.$headquarter -> id;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $headquarter -> fill($input);
            $update = $headquarter -> update();
            $type = $update ? 'success' : 'fail';

            return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
        }
        else
        {
            $errors = $validator -> messages() -> toArray();
            $errMsg = $this->ajaxErrors($errors,[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $errMsg]);
        }
    }

    public function postEditLogo()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();

        $headquarter = $this -> headquarter;

        $postData = \Input::get();
        $photo    = new \Services\FilesUploader('headquarters');
        $path     = $photo -> getUploadPath($headquarter -> id);
        $logo     = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$headquarter -> logo);
        $headquarter -> logo = $logo;
        $update   = $headquarter -> update();
        $type     = $update ? 'success' : 'fail';

        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type),'url' => $headquarter -> logo]);
    }
}