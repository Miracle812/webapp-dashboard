<?php namespace Modules;

class Profile extends Modules
{
    public $layout;
    protected $options = [];

    public function __construct()
    {
        $this->activateUserSection();
        $this->user = \Auth::user();
    }

    public function getEditGeneral()
    {
        $timezonesArray = \Model\Timezones::all()->lists('name','identifier');
        return \View::make($this->regView('modal_edit_general'), compact('timezonesArray'));
    }

    public function getEditAvatar()
    {
        return \View::make($this->regView('modal_edit_avatar'));
    }

    public function getEditPassword()
    {
        return \View::make($this->regView('modal_edit_password'));
    }

    public function getAvatar()
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find(\Auth::user() -> id);
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

        $passLng = \Services\PasswordRules::$options['length'];
        $rules = array(
            'current_password'      => 'required',
            'password'              => 'required|min:'.$passLng.'|confirmed',
            'password_confirmation' => 'required'
        );

        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $errMsg = \Services\PasswordRules::check($newPass);
            if($errMsg)
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'form_errors' => ['password'=>[$errMsg]]]);
            $user = \Auth::user();
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
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'form_errors' => $this->ajaxErrors($errors,[])]);
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
            'username'   => 'required|min:5|unique:users,username,'.\Auth::user() -> id,
            'email'      => 'required|email|unique:users,email,'.\Auth::user() -> id
        ];

        $validator = \Validator::make($input, $rules);
        $errors = $validator->messages()->toArray();
        if(empty($errors) )
        {
            $user = \Auth::user();
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
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'form_errors' => $errMsg]);
        }
    }

    public function postEditAvatar()
    {
        $user     = \Auth::user();
        $photo    = new \Services\FilesUploader('profile');
        $path     = $photo -> getUploadPath($user -> id);
                    $photo -> checkCreatePath($path);
        $file     = \Input::file('image');
        $uploaded = $photo->Uploadify($file,$path);

        \File::delete(public_path().$user -> avatar);
        $user -> avatar = $path.$uploaded;
        $user -> update();
        return \Response::json(['url' => $user -> avatar]);
    }
}