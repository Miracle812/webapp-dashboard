<?php namespace Sections\Admins;

class Users extends AdminsSection {

    protected $user;
    protected $role;
    protected $roles = ['admin','hq-manager','area-manager','local-manager','visitor','accountant','client-relation-officer','new-local-manager'];
    protected $permission;

    public function __construct(\User $user, \Role $role, \Permission $permission)
    {
        parent::__construct();
        $this -> user = $user;
        $this -> role = $role;
        $this -> permission = $permission;
        $this -> headquarters = \Model\Headquarters::with('units')->get();
        $this -> breadcrumbs -> addCrumb('users', 'Users');
    }

    public function getRules($type, $user)
    {
        $rules = $user->rules;
        \Input::merge(['mobile_phone' => preg_replace('/\D/', '', \Input::get('mobile_phone'))]);

        $role = \Role::find(\Input::get('role'));
        if(!$role || !in_array($role->name,$this->roles))
            \Input::merge(['role' => '']);

        $inputUnits = is_array(\Input::get('units')) ? \Input::get('units') : [\Input::get('units')];
        if(!\Input::get('units')|| !array_intersect($inputUnits,\Model\Units::whereActive(1)->get()->lists('id'))) {
            \Input::merge(['units' => '']);
        }

        $passLng = \Services\PasswordRules::$options['length'];
        $rules['units']      = 'required';
        $rules['role']       = 'required|numeric';
        $rules['username']   = 'required|between:3,20|alpha_num|unique:users';
        $rules['password']   = 'required|min:'.$passLng.'|alpha_num|confirmed';
        $rules['password_confirmation'] = 'required|same:password';

        if($type == 'create'){
            if($role -> name == 'visitor'){
                \Input::merge(['username' => 'visitor_' . strtotime('now')]);
                \Input::merge(['password' => ($pass = \Str::random())]);
                \Input::merge(['password_confirmation' => $pass]);
                \Input::merge(['confirmed' => 0]);
            }
        }
        elseif ($type == 'edit') {
            unset($rules['username'],$rules['password'],$rules['password_confirmation']);
            $rules['email']      = 'required|email|unique:users,email,'.$user -> id;
            $rules['username']   = 'required|between:3,20|alpha_num|unique:users,username,'.$user -> id;
        }



        if(in_array($role -> name, ['visitor'])){
            unset($rules['username'],$rules['password']);
            $rules['expiry_date'] = 'required';
        }
        else{
            if(!\Input::get('confirmed'))
                \Input::merge(['confirmed' => 0]);
        }

        if(in_array($role -> name, ['admin','hq-manager','accountant'])){
            unset($rules['units']);
        }

        return $rules;
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getCreate()
    {
        $timezonesArray = \Model\Timezones::all() -> lists('name','identifier');
        $headquarters = $this -> headquarters;
        $roles = \Role::whereIn('name',$this->roles) -> lists('name','id');
        foreach($roles as $key => $role){
            $roles[$key] = \Lang::get('/common/roles.'.$role);
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs', 'roles', 'headquarters', 'timezonesArray'));
    }

    public function postCreate()
    {
        $user = new \User();
        $rules = $this->getRules('create', $user);
        $validator = \Validator::make(\Input::all(),$rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            if((\Input::get('password') !== 4) && ($errMsg = \Services\PasswordRules::check(\Input::get('password')))){
                return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.create_fail'), 'errors' => $this->ajaxErrors(['password' => [$errMsg]], [])]);
            }
            $repo = \App::make('UserRepository');
            $user = $repo -> signup(\Input::all());
            if ($user->id) {
                $user -> saveRoles(\Input::get('role'));
                $repo -> saveHqsUnits($user,\Input::all());
                $user -> fill(\Input::all());
                $user -> lang = 'en';
                $user -> active = 1;
                $update = $user -> update();
                if(!$user->confirmed){
                    $repo->sendConfirmEmail($user);
                }
                \Services\AutoMessages::create($user);
                $type = $update ? 'success' : 'fail';
                return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.create_'.$type)]);
            }
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getEdit($id)
    {
        $user = \User::find($id);
        if(!$user){
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        }
        $roles = \Role::whereIn('name',$this->roles) -> lists('name','id');
        foreach($roles as $key => $role){
            $roles[$key] = \Lang::get('/common/roles.'.$role);
        }
        $threadOpt = \Model\OptionsMenu::whereIdentifier($user->getTable())->first();
        $timezonesArray = \Model\Timezones::all()->lists('name','identifier');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs', 'user', 'roles', 'timezonesArray','threadOpt'));
    }

    public function postEdit($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user){
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        }
        $rules = $this->getRules('edit', $user);
        $validator = \Validator::make(\Input::all(),$rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors))
        {
            $repo = \App::make('UserRepository');
            $user -> saveRoles(\Input::get('role'));
            $repo -> saveHqsUnits($user,\Input::all());
            $user -> fill(\Input::all());
            $update = $user -> update();
            $type = $update ? 'success' : 'fail';
            return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getEditPassword($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('modal.edit-password'), compact('breadcrumbs', 'user'));
    }

    public function postEditPassword($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $passLng = \Services\PasswordRules::$options['length'];

        $rules['password']   = 'required|min:'.$passLng.'|alpha_num|confirmed';
        $rules['password_confirmation'] = 'required|same:password';

        $validator = \Validator::make(\Input::all(), $rules);
        $errors = $validator -> messages() -> toArray();
        if(empty($errors) )
        {
            $errMsg = \Services\PasswordRules::check(\Input::get('password'));
            if($errMsg)
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => ['password'=>[$errMsg]]]);
            $user -> password = \Input::get('password');
            $user -> password_confirmation = \Input::get('password_confirmation');
            $user -> update();
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('/common/messages.update_success')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.update_fail'), 'errors' => $this->ajaxErrors($errors,[])]);
        }
    }

    public function getEditAvatar($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('modal.edit-avatar'), compact('breadcrumbs', 'user'));
    }

    public function postEditAvatar($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user){
            return \Response::json(['type' => 'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        }
        $postData = \Input::get();
        $photo    = new \Services\FilesUploader('users');
        $path     = $photo -> getUploadPath($user -> id);
        $avatar   = $photo -> avatarUploader($postData, $path);
        \File::delete(public_path().$user -> avatar);
        $user -> avatar = $avatar;
        $user -> update();
        return \Response::json(['url' => $avatar]);
    }

    public function getActive($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        $user -> active = $user -> active ? 0 : 1;
        $update = $user -> update();
        $type = $update ? 'success' : 'fail';
        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    }

    public function getConfirmed($id)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $user = \User::find($id);
        if(!$user)
            return \Response::json(['type'=>'fail', 'msg' => \Lang::get('/common/messages.not_exist')]);
        $user -> confirmed = $user -> confirmed ? 0 : 1;
        $update = $user -> update();
        $type = $update ? 'success' : 'fail';
        return \Response::json(['type'=>$type, 'msg'=>\Lang::get('/common/messages.update_'.$type)]);
    }

    public function getDelete($id)
    {
        $user = \User::find($id);
        if(!$user)
            return $this -> redirectIfNotExist();
        $delete = $user -> delete();
        $type   = $delete ? 'success' : 'fail';
        return \Redirect::to('/users')->with($type, \Lang::get('/common/messages.delete_'.$type));
    }

    public function getCreateFormFields($id)
    {
        $timezonesArray = \Model\Timezones::all() -> lists('name','identifier');
        $headquarters = $this -> headquarters -> lists('name','id');
        $role = \Role::find($id);
        return \View::make($this->regView('create.'.$role->name), compact('timezonesArray', 'headquarters'))->render();
    }

    public function getUploadUnitsField($type,$hqId,$roleId,$userId=null)
    {
        $data = null;
        $role = \Role::find($roleId);
        if(!$role || in_array($role ->name, ['admin','hq-manager'])){
            return null;
        }
        $multiple = ($role && in_array($role->name,['area-manager','client-relation-officer'])) ? true : false;

        if(($type =='edit') && $userId){
            $user = \User::find($userId);
            $data = $user->units ? $user->units->lists('id') : null;
        }
        $headquarter = \Model\Headquarters::find($hqId);
        $units = $headquarter->units->lists('name','id');
        return \View::make($this->regView('partials.upload-units-field'), compact('type','data','units','multiple'))->render();
    }

    public function getEditFormFields($userId,$roleId)
    {
        $timezonesArray = \Model\Timezones::all() -> lists('name','identifier');
        $headquarters = $this -> headquarters;
        $role = \Role::find($roleId);
        $user = \User::find($userId);
        return \View::make($this->regView('edit.'.$role->name), compact('timezonesArray','headquarters','user'))->render();
    }

    public function getSendConfirmEmail($id)
    {
        $user = \User::find($id);
        if($user) {
            $repo = \App::make('UserRepository');
            $repo->sendConfirmEmail($user);
            return \Response::json(['type' => 'success', 'msg' => 'Email with confirmation link has been sent.']);
        }
    }

    public function getSendAccessEmail($id)
    {
        $user = \User::find($id);
        if($user) {
            $repo = \App::make('UserRepository');
            $repo->sendVisitorAccessUrl($user);
            return \Response::json(['type' => 'success', 'msg' => 'Email with access link has been sent.']);
        }
    }

    public function getDatatable($role = null)
    {
        if(!\Request::ajax())
            return $this->redirectIfNotExist();
        $users = new \User();

        if($role){
            $users = $users->whereHas(
                'roles', function($query) use($role) {
                $query -> whereName($role);
            });
        }
        $users = $users -> get();
        $options = [];
        if ($users->count()){
            $repo = \App::make('UserRepository');
            foreach ($users as $user)
            {
                $options[] = [
                    strtotime($user->created_at),
                    $user->fullname(),
                    $user->email,
                    implode(',',$repo->getUserRoles($user)),
                    implode(',',$repo->getUserHeaduarters($user)),
                    ((($count = count($repo->getUserUnits($user)))>1)?('Units: '.$count):implode(',',$repo->getUserUnits($user))),
                    '<div class="text-center">'.(\HTML::ownOuterBuilder(
                        \HTML::ownButton($user -> id,'users','active','fa-'.($user->active?'check':'times'),'btn-'.( $user->active?'success':'default').' ajaxAction'),'div','text-center','')).'</div>',
                    '<div class="text-center">'.(\HTML::ownOuterBuilder(
                        \HTML::ownButton($user -> id,'users','confirmed','fa-'.($user->confirmed?'check':'times'),'btn-'.( $user->confirmed?'success':'default').' ajaxAction'),'div','text-center','')).'</div>',
                    '<div class="text-center">'.(\HTML::ownOuterBuilder(
                        \HTML::ownDropdown($repo->getDropdownActions($user),'Select','btn-orange')
                    )).'</div>',
                    '<div class="text-center">'.(\HTML::ownOuterBuilder(
                        \HTML::ownButton($user -> id,'users','delete','fa-times','btn-danger')
                    )).'</div>',
                ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }
}