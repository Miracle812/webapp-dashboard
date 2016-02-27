<?php namespace Sections\Front;

class Messages extends FrontSection
{
    public function getRedirect($encrypted)
    {
        $data = explode(',',\Crypt::decrypt($encrypted));
        $rules = [
            '0'=>'required|integer',
            '1'=>'required',
            '2'=>'required|integer',
            '3'=>'required|integer',
        ];
        $validator = \Validator::make($data, $rules);
        if (!$validator -> fails()){
            list($recipientId,$recipientType,$threadId,$timestamp) = $data;
            $messageRecipient = \Model\MessagesRecipients::whereRecipientId($recipientId)->whereRecipientType($recipientType)->whereMessageId($threadId)->first();
            $recipient = $messageRecipient -> recipient();
            if($recipient) {
                $table = $recipient -> getTable();
                $user = [];
                switch ($table) {
                    case 'users' :
                        $url = '/messages/thread/' . $threadId;
                        return $recipient->hasRole('hq-manager') ? \Redirect::to($url) : \Redirect::to($url);
                        break;
                    case 'suppliers' :
                    case 'units-contacts' :
                        $user['temporary-auth-messages'] = $table.','.$recipient->id.','.$threadId;
                        \Session::put($user);
                        return \Redirect::to('messages-access/thread/' . $threadId);
                        break;
                    default:
                        return \Redirect::to('/');
                        break;
                }
            }
            else{
                return \Redirect::to('/');
            }
        }
        else {
            return \Redirect::to('/');
        }
    }
    public function getThread(\Model\Messages $thread)
    {
        if(!\Session::has('temporary-auth-messages') || !$thread || ($thread->thread_id > 0))
            return \Redirect::to('/');
        $auth = \Session::get('temporary-auth-messages');
        $auth = explode(',',$auth);
        $rules = [
            '0'=>'required',//recipient-type
            '1'=>'required|integer',//recipient-id
            '2'=>'required|integer',//thread-id
        ];
        $validator = \Validator::make($auth, $rules);
        if (!$validator -> fails()){
            $recipient = \Model\MessagesRecipients::whereRecipientType($auth[0])->whereRecipientId($auth[1])->whereMessageId($thread->id)->first();
            return \View::make($this->regView('thread'), compact('thread','recipient'));
        }
    }

    public function postThread(\Model\Messages $thread)
    {
        if(!\Session::has('temporary-auth-messages') || !$thread)
            return \Redirect::to('/');
        $auth = \Session::get('temporary-auth-messages');
        $auth = explode(',',$auth);
        $sessRules = [
            '0'=>'required',//recipient-type
            '1'=>'required|integer',//recipient-id
            '2'=>'required|integer',//thread-id
        ];
        $threadRules = $thread->rules['thread'];
        $inputs = \Input::all();
        $sessionValid = \Validator::make($auth, $sessRules);
        $inputsValid = \Validator::make( $inputs, $threadRules);
        $save = false;
        if (!$sessionValid -> fails() && !$inputsValid -> fails()){
            $recipient = \Model\MessagesRecipients::whereRecipientType($auth[0])->whereRecipientId($auth[1])->whereMessageId($thread->id)->first();

            if($recipient){
                $recipient = $recipient -> recipient();
                $new = new \Model\Messages();
                $new -> fill($inputs);
                $new -> thread_id = $thread->id;
                $new -> author_id = $recipient->id;
                $new -> author_type = $recipient->getTable();
                $save = $new -> save();
            }
        }

        $type = $save ? 'success' : 'fail';
        $msg  = $save ? \Lang::get('/common/messages.create_success') : \Lang::get('/common/messages.create_fail');

        if($save){
            \Services\Messages::sendMessages($new);
            return \Redirect::back()->with($type, $msg);
        }
        else{
            if ($inputsValid -> fails()){
                return \Redirect::back()->withInput()->withErrors($inputsValid);
            }
            elseif ($sessionValid -> fails()){
                return \Redirect::back()->withErrors($sessionValid);
            }
            else{
                return \Redirect::back();
            }
        }

    }











    public function getLockMe()
    {
        $sessId = \Session::getId();
        \Session::put('locked', $sessId);
        $user = $this->auth_user;
        return \View::make($this->regView('lock-me'), compact('user'));
    }

    public function postLockMe()
    {
        $password = \Input::get('password');
        if (!$password)
            return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.enter_password')]);
        else {
            if ($user = \Auth::user()) {
                if (\Hash::check(\Input::get('password'), $user->password)) {
                    \Session::forget('locked');
                    return \Response::json(['type' => 'success']);
                } else {
                    return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.wrong_password')]);
                }
            } else {
                return \Response::json(['type' => 'error', 'msg' => \Lang::get('/common/messages.user_sess_not_exist')]);
            }
        }
    }

    public function postLocked()
    {
        $password = \Input::get('password');
        if (!$password)
            return \Redirect::to('/locked')->with(['error' => \Lang::get('/common/messages.enter_password')]);
        else {
            if ($user = \Auth::user()) {
                if (\Hash::check(\Input::get('password'), $user->password)) {
                    \Session::forget('locked');
                    return \Redirect::to('/login');
                } else {
                    return \Redirect::to('/locked')->with(['error' => \Lang::get('/common/messages.wrong_password')]);
                }
            } else {
                return \Redirect::to('/locked')->with(['error' => \Lang::get('/common/messages.user_sess_not_exist')]);
            }
        }
    }

    public function getLocked()
    {
        $user = $this->auth_user;
        return \View::make($this->regView('locked'), compact('user'));
    }

    public function getLogin()
    {
        $user = \Auth::user();
        if (!empty ($user -> id)) {
            return \Redirect::to($user -> getUserSection());
        }
        return \View::make($this -> regView('login'));
    }

    public function getVisitorLogin($username,$token)
    {
        $user = \User::whereConfirmationCode($token)->first();
        $errors = [];
        if ($user && $user->hasRole('visitor')) {
            if (!$user->confirmed)
                $errors[] = \Lang::get('/common/confide.visitor.partials.not_confirmed');
            if (!$user->active)
                $errors[] = \Lang::get('/common/confide.visitor.partials.not_active');
            if ($user->username !== $username)
                $errors[] = \Lang::get('/common/confide.visitor.partials.not_active');
            if (strtotime($user -> expiry_date()) < strtotime('now'))
                $errors[] = \Lang::get('/common/confide.visitor.partials.expired');
        }
        else{
            $errors[] = 'not exist';
        }

        if(count($errors))
            return \Redirect::to('confirmation')
                ->with( 'type', 'error' )
                ->with( 'msg' , \Lang::get('/common/confide.visitor.partials.your_account').' '.implode(' '.\Lang::get('/common/confide.visitor.partials.and_or').' ', $errors).'.' )
                ->with( 'user', $user );
        else {
            \Auth::loginUsingId($user->id);
            return \Redirect::to($user->getUserSection());
        }
    }


    public function postLogin()
    {
        if(\Auth::check())
            \Auth::logout(\Auth::user());

        $input = array(
            'email'    => \Input::get( 'email' ), // May be the username too
            'username' => \Input::get( 'email' ),
            'password' => \Input::get( 'password' ),
            'remember' => \Input::get( 'remember' ),
        );

        $repo = \App::make('UserRepository');

        if ( \Confide::logAttempt( $input, true ) )
        {
            $repo -> input = $input;
            $err_msg=false;
            if($repo->checkPassword()){
                if ($repo->existsButUserNotActive()) {
                    $err_msg = \Lang::get('common/confide.alerts.user_not_actived');
                }
                elseif (($repo->isLocalManager() || $repo->isVisitor()) && $repo->existsButNotUnit()) {
                    $err_msg = \Lang::get('common/confide.alerts.unit_not_exist_or_not_actived');
                }
                elseif (!$repo->isAdmin() && $repo->existsButNotHq()) {
                    $err_msg = \Lang::get('common/confide.alerts.hq_not_exist_or_not_actived');
                }
            }

            if($err_msg){
                \Session::forget('loginRedirect');
                \Auth::logout();
                return \Redirect::to('login')
                    ->withInput(\Input::except('password'))
                    ->with( 'error', $err_msg );
            }
            else {
                $user = \Auth::user();
                $r = \Session::get('loginRedirect');
                if (!empty($r)) {
                    \Session::forget('loginRedirect');
                    return \Redirect::to($r);
                }
                return \Redirect::to($user->getUserSection());
            }
        }
        else
        {
            $input = \Input::all();

            if ($repo->login($input)) {
                return \Redirect::intended('/');
            } else {
                if ($repo->isThrottled(array_get($input,'email'))) {
                    $err_msg = \Lang::get('confide::confide.alerts.too_many_attempts');
                } elseif ($repo->existsButNotConfirmed($input)) {
                    $err_msg = \Lang::get('confide::confide.alerts.not_confirmed');
                } else {
                    $err_msg = \Lang::get('confide::confide.alerts.wrong_credentials');
                }
                return \Redirect::to('login')
                    ->withInput(\Input::except('password'))
                    ->with( 'error', $err_msg );
            }
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function getConfirm( $code )
    {
        $user = \User::where('confirmation_code','=',$code)->first();
        if ($user && \Confide::confirm( $code ) )
        {
            $succMsg = \Lang::get('confide::confide.alerts.confirmation');
            $response = ['type' => 'success', 'msg' => $succMsg];
            if($user->hasRole('visitor')){
                $response = ['type' => 'visitor', 'msg' => $response['msg'] .'<br><br>'.\Lang::get('confide::confide.visitor.send_email_token')];
                $repo = \App::make('UserRepository');
                $repo->sendVisitorAccessUrl($user);
            }
        }
        else
        {
            $response = ['type' => 'error', 'msg' => \Lang::get('confide::confide.alerts.wrong_confirmation')];
        }

        return \Redirect::to('confirmation')
            ->with( 'type', $response['type'] )
            ->with( 'msg' , $response['msg'] )
            ->with( 'user', $user );
    }

    public function getConfirmation( )
    {
        if(!\Session::get('type'))
            return \Redirect::to('/');
        $type = \Session::get('type');
        $msg  = \Session::get('msg');
        $user = \Session::get('user');
        return \View::make($this->regView('confirmation'), compact('user','type','msg'));
    }

    /**
     * Attempt to reset password with given email
     *
     */
    public function postForgot()
    {
        if( \Confide::forgotPassword( \Input::get( 'email' ) ) )
        {
            return \Response::json(['type'=>'success', 'msg'=>\Lang::get('confide::confide.alerts.password_forgot')]);
        }
        else
        {
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('confide::confide.alerts.wrong_password_forgot')]);
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function getReset( $token )
    {

        $this -> view = 'reset';
        $this->params = [
            'token' => $token
        ];
        return \View::make($this->regView('reset'), compact('token'));
    }

    /**
     * Attempt change password of the user
     *
     */
    public function postReset()
    {
        $repo = \App::make('UserRepository');
        $input = array(
            'token'                 =>\Input::get('token'),
            'password'              =>\Input::get('password'),
            'password_confirmation' =>\Input::get('password_confirmation'),
        );

        // By passing an array with the token, password and confirmation
        if ($repo->resetPassword($input)) {
            $notice_msg = \Lang::get('confide::confide.alerts.password_reset');
            //return \Redirect::action('UsersController@login')
            return \Redirect::to('/login')
                ->with('notice', $notice_msg);
        } else {
            $error_msg = \Lang::get('confide::confide.alerts.wrong_password_reset');
            //return \Redirect::action('UsersController@resetPassword', array('token'=>$input['token']))
            return \Redirect::to('/reset/'.$input['token'])
                ->withInput()
                ->with('error', $error_msg);
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function getLogout()
    {
        \Session::forget('locked');
        \Confide::logout();
        return \Redirect::to('/login');
    }

    public function processRedirect($url1,$url2,$url3)
    {
        $redirect = '';
        if( ! empty( $url1 ) )
        {
            $redirect = $url1;
            $redirect .= (empty($url2)? '' : '/' . $url2);
            $redirect .= (empty($url3)? '' : '/' . $url3);
        }
        return $redirect;
    }

    public function getModalConfirmDelete()
    {
        return \View::make('_default/modals/confirm_delete');
    }
}