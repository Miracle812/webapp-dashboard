<?php

class UserRepository
{

    var $input;
    public function signup($input)
    {
        $user = new User;
        $user->username = array_get($input, 'username');
        $user->email    = array_get($input, 'email');
        $user->password = array_get($input, 'password');
        $user->confirmed = array_get($input, 'confirmed');
        $user->password_confirmation = array_get($input, 'password_confirmation');
        $user->confirmation_code     = md5(uniqid(mt_rand(), true));
        $this->save($user);
        return $user;
    }

    public function login($input)
    {
        if (! isset($input['password'])){
            $input['password'] = null;
        }
        return Confide::logAttempt($input, Config::get('confide::signup_confirm'));
    }

    public function isThrottled($input)
    {
        return Confide::isThrottled($input);
    }

    public function existsButNotConfirmed($input)
    {
        $user = Confide::getUserByEmailOrUsername($input);
        if ($user) {
            $correctPassword = Hash::check(
                isset($input['password']) ? $input['password'] : false,
                $user->password
            );
            return (! $user->confirmed && $correctPassword);
        }
    }

    public function checkUser(){
        return Confide::getUserByEmailOrUsername($this->input);
    }

    public function checkPassword()
    {
        $user = $this->checkUser();
        if($user){
            return $correctPassword = Hash::check(
                isset($this->input['password']) ? $this->input['password'] : false,
                $user->password
            );
        }
        return false;
    }

    public function existsButUserNotActive()
    {
        $user = $this->checkUser();
        if ($user) {
            return (! $user->active );
        }
    }

    public function existsButWithoutUnit()
    {
        $user = $this->checkUser();
        if ($user) {
            return (! $user -> unit() );
        }
    }

    public function existsButUnitNotActive()
    {
        $user = $this->checkUser();
        if ($user) {
            return (! $user -> unit() -> active );
        }
    }

    public function existsButWithoutHq()
    {
        $user = $this->checkUser();
        if ($user) {
            return (! $user -> unit() -> headquarter );
        }
    }

    public function existsButHqNotActive()
    {
        $user = $this->checkUser();
        if ($user) {
            return (! $user -> unit() -> headquarter -> active );
        }
    }

    public function existsButNotUnit()
    {
        $user = $this->checkUser();
        if ($user) {
            return (!$user -> unit()  || !$user -> unit() -> active);
        }
    }

    public function existsButNotHq()
    {
        $user = $this->checkUser();
        if ($user) {
            if($user->hasRole('local-manager') || $user->hasRole('visitor'))
                return (!$user -> unit() -> headquarter || !$user -> unit() -> headquarter -> active);
            elseif($user->hasRole('hq-manager'))
                return (!$user -> headquarter() || !$user -> headquarter() -> active);
        }
    }

    public function isLocalManager()
    {
        $user = $this->checkUser();
        if ($user) {
            return ( $user->hasRole('local-manager'));
        }
    }

    public function isVisitor()
    {
        $user = $this->checkUser();
        if ($user) {
            return ( $user->hasRole('visitor'));
        }
    }

    public function isAreaManager()
    {
        $user = $this->checkUser();
        if ($user) {
            return ( $user->hasRole('area-manager'));
        }
    }

    public function isHqManager()
    {
        $user = $this->checkUser();
        if ($user) {
            return ( $user->hasRole('hq-manager'));
        }
    }

    public function isAdmin()
    {
        $user = $this->checkUser();
        if ($user) {
            return ( $user->hasRole('admin'));
        }
    }

    public function resetPassword($input)
    {
        $result = false;
        $user   = Confide::userByResetPasswordToken($input['token']);

        if ($user) {
            $user->password              = $input['password'];
            $user->password_confirmation = $input['password_confirmation'];
            $result = $this->save($user);
        }

        // If result is positive, destroy token
        if ($result) {
            Confide::destroyForgotPasswordToken($input['token']);
        }

        return $result;
    }

    public function save(User $instance)
    {
        return $instance->save();
    }

    public function sendConfirmEmail(User $user)
    {
        return \Mail::send(
            \Config::get('confide::email_account_confirmation'),
            compact('user'),
            function ($message) use ($user) {
                $message
                    ->to($user->email, ($user->first_name.' '.$user->surname))
                    ->subject(\Lang::get('confide::confide.email.account_confirmation.subject'));
            }
        );
    }

    public function sendVisitorAccessUrl(\User $user)
    {
        if($user->hasRole('visitor'))
            return \Mail::send(
                'emails.visitors.access_url',
                compact('user'),
                function ($message) use ($user) {
                    $message
                        ->to($user->email, ($user->first_name.' '.$user->surname))
                        ->subject(\Lang::get('Visitor Access Link'));
                }
            );
        return false;
    }

    public function getDropdownActions(\User $user)
    {
        $classIco = 'm-r text-navitas fa';
        $actions = [];
        $actions[] = ['users/edit/'.$user->id => ['<i class="'.$classIco.' fa-pencil"></i>Edit User Data','text-primary']];
        $actions[] = ['<li class="divider"></li>'];
        $actions[] = ['users/edit/avatar/'.$user->id => ['<i class="'.$classIco.' fa-user"></i>Edit Avatar','text-default','ajaxModal']];
        if(!$user->hasRole('visitor')) {
            $actions[] = ['users/edit/password/'.$user->id => ['<i class="'.$classIco.' fa-key"></i>Edit Password','text-default','ajaxModal']];
        }
        $actions[] = ['<li class="divider"></li>'];
        $actions[] = ['users/send-confirm-url/'.$user->id => ['<i class="'.$classIco.' fa-link m-r"></i>Send confirm link','text-success ajaxAction']];
        if($user->hasRole('visitor')) {
            $actions[] = ['users/send-access-url/'.$user->id => ['<i class="'.$classIco.' fa-link"></i>Send access link','text-primary ajaxAction']];
        }
        return $actions;
    }

    public function getUserRoles(\User $user)
    {
        $roles = $user -> roles->lists('name');
        foreach($roles as $key => $role){
            $roles[$key] = \Lang::get('/common/roles.'.$role);
        }
        return $roles;
    }
    public function getUserHeaduarters(\User $user)
    {
        $headquarters = $user -> headquarters;
        return ($headquarters -> count()) ? $headquarters->lists('name','id') : [\Lang::get('/common/general.na')];
    }
    public function getUserUnits(\User $user)
    {
        $units = $user -> units;
        return ($units -> count()) ? $units->lists('name','id') : [\Lang::get('/common/general.na')];
    }

    public function saveHqsUnits(\User $user, $inputs)
    {
        switch ($user -> role() -> name) {
            case 'accountant' :
            case 'admin' :
                $user->saveHeadquarter([]);
                $user->saveUnits([]);
                break;
            case 'hq-manager' :
                $user->saveHeadquarter($inputs['headquarter']);
                $user->saveUnits([]);
                break;
            case 'local-manager' :
                $user->saveUnits($inputs['units']);
                $user->saveHeadquarter($inputs['headquarter']);
                break;
            case 'new-local-manager' :
                $user->saveUnits($inputs['units']);
                $user->saveHeadquarter($inputs['headquarter']);
                break;
            case 'client-relation-officer' :
                $user->saveUnits(array_filter($inputs['units']));
                $user->saveHeadquarter([]);
                break;
            case 'area-manager' :
                $user->saveUnits($inputs['units']);
                $user->saveHeadquarter($inputs['headquarter']);
                break;
            case 'visitor' :
                $user->saveUnits($inputs['units']);
                $user->saveHeadquarter($inputs['headquarter']);
                $user->saveExpiryDate($inputs['expiry_date'] . ' 23:59:59');
                break;
        }
    }
}