<?php namespace Sections\AreaManagers;

class Profile extends AreaManagersSection {

    public $section = 'profile';

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('profile', 'Profile');
    }

    public function getEditGeneral()
    {
        $this -> breadcrumbs -> addLast( $this -> setAction('edit_general') );
        $timezonesArray = \Model\Timezones::all()->lists('name','identifier');
        return \View::make($this->regView('modal_edit_general'), compact('timezonesArray'));
    }

    public function getEditAvatar()
    {
        $this -> breadcrumbs -> addLast( $this -> setAction('edit_avatar') );
        return \View::make($this->regView('modal_edit_avatar'));
    }

    public function getEditPassword(){
        $this -> breadcrumbs -> addLast( $this -> setAction('edit_password') );
        return \View::make($this->regView('modal_edit_password'));
    }

    public function getAvatar()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($this -> auth_user -> id);
        $avatar = '';
        if($user -> avatar)
        {
            $avatar = $user -> avatar;
        }
        return \Response::json(['image' => $avatar]);
    }

    public function postEditPassword()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $formData = \Input::get();
        $oldPass = $formData['current_password'];
        $newPass = $formData['password'];
        $confirm = $formData['password_confirmation'];

        $rules = array(
            'current_password'      => 'required',
            'password'              => 'required|min:5|confirmed',
            'password_confirmation' => 'required'
        );
        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $user = $this -> auth_user;
            if(!\Hash::check($oldPass, $user->password))
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.old_pass_not_match')]);
            $user -> password = $newPass;
            $user -> password_confirmation = $confirm;
            unset($user -> role);
            $user -> update();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function postEditGeneral()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $input    = \Input::all();
        $rules = [
            'timezone'   => 'required',
            'first_name' => 'required|min:2',
            'surname'    => 'required|min:3',
            'username'   => 'required|min:5|unique:users,username,'.$this -> auth_user -> id,
            'email'      => 'required|email|unique:users,email,'.$this -> auth_user -> id
        ];

        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        if(empty($errors) )
        {
            $user = $this -> auth_user;
            $user -> fill($input);
            unset($user -> role);
            $update = $user -> update();
            if($update)
                return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($errors,[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $errMsg]);
        }
    }

    public function postEditAvatar()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = $this -> auth_user;
        $postData = \Input::get();
        $photo    = new \Services\FilesUploader($this -> section);
        $path     = $photo -> getUploadPath($user -> id);
        $avatar   = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$user -> avatar);
        $user -> avatar = $avatar;
        $user -> update();
        return \Response::json(['url' => $avatar]);
    }
}