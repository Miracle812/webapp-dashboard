<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Confide\ConfideUserInterface;
use Zizaco\Entrust\HasRole;
use Carbon\Carbon;

class User extends Eloquent implements ConfideUserInterface
{
    use ConfideUser;
    use HasRole;

    public $navitasRoles;
    public $userRole;

    protected $fillable = [
        'first_name',
        'surname',
        'username',
        'email',
        'phone',
        'mobile_phone',
        'timezone',
        'confirmed'
    ];

    public $rules = [
        'first_name'=> 'required|min:3',
        'surname'   => 'required|min:3',
        'username'  => 'required|between:3,20|alpha_num|unique:users,username',
        'email'     => 'required|email|unique:users',
        'timezone'  => 'required',
        'mobile_phone' => 'required',
    ];

    public static function model($model = __CLASS__)
    {
        return new $model;
    }

    public function repository()
    {
        return \App::make('\Repositories\Users', [$this]);
    }

    public function timezone()
    {
        return $this->belongsTo('\Model\Timezones', 'identifier', 'timezone');
    }

    public function stats()
    {
        return $this->hasMany('\Model\UsersStatistics', 'user_id');
    }

    public function lastStats()
    {
        return $this->stats()->whereRaw('id IN (SELECT max(id) FROM users_statistics GROUP BY user_id)')->first();
    }

    public function role()
    {
        return $this->roles()->first();
    }

    public function route()
    {
        return '';
    }

    public function unit()
    {
        if($this->units()->count())
        {
            return $this->units()->first();
        }
        else{
            return false;
        }
    }

    public function isOnline()
    {
        return  \DB::table('sessions')->whereUserId($this->id)->first();
    }

    public function assigned_expiry_date()
    {
        return $this->hasOne('\Model\AssignedExpiryDate');
    }

    public function headquarters()
    {
        return $this->belongsToMany('\Model\Headquarters', 'assigned_headquarters','user_id','headquarter_id');
    }

    public function units()
    {
        return $this->belongsToMany('\Model\Units', 'assigned_units','user_id','unit_id');
    }

    public function messages()
    {
        return $this->belongsToMany('\Model\Messages','messages_recipients','user_id','message_id')->orderBy('id','DESC');
    }

    public function lastGroupedMessages()
    {
        $msgsIds = [];
        $parentMessages = $this -> messages -> filter(function($item){
           return ($item -> thread_id == 0) ? true : false;
        });
        foreach ($parentMessages as $parent){
            if($parent->childs->count()){
                $childsIds = $parent->childs()->whereRaw('id IN (SELECT max(id) FROM messages WHERE author_id NOT IN ('.\Auth::user()->id.') GROUP BY thread_id)')->lists('id');
                if(!count($childsIds) && !$parent->imAuthor()){
                    $childsIds = [$parent->id];
                }
                $msgsIds = array_merge($msgsIds, $childsIds);
            }
            else{
                if(!$parent->imAuthor())
                    $msgsIds = array_merge($msgsIds, [$parent->id]);
            }
        }
        return \Model\Messages::whereIn('id',$msgsIds)->orderBy('id','desc')->get();
    }

    public function headquarter()
    {
        return $this->headquarters()->first();
    }

    public function signature()
    {
        return $this->hasOne('\Model\Signatures');
    }

    public function getUnitManagers($id_unit)
    {
        return \User::where('unit_id','=',$id_unit)->get();
    }

    public function saveRoles($inputRoles)
    {
        if($inputRoles){
            $this->roles()->sync([$inputRoles]);
        } else {
            $this->roles()->detach();
        }
    }

    public function expiry_date()
    {
        $expDate = $this->assigned_expiry_date()->first();
        return $expDate?$expDate->expiry_date:null;
    }

    public function saveUnits($units)
    {
        $units = is_array($units) ? $units : [$units];
        if(! empty($units)) {
            $this->units()->sync($units);
        } else {
            $this->units()->detach();
        }
    }

    public function saveHeadquarter($inputHq)
    {
        if(! empty($inputHq)) {
            $this->headquarters()->sync([$inputHq]);
        } else {
            $this->headquarters()->detach();
        }
    }

    public function saveExpiryDate($inputDate)
    {
        $aDate = $this -> assigned_expiry_date();
        if($aDate->first()){
            $aDate ->  update(['expiry_date' => $inputDate]);
        }
        else{
            $expDate = new \Model\AssignedExpiryDate();
            $expDate -> expiry_date = $inputDate;
            $aDate->save($expDate);
        }
        return $aDate;
    }

    public function fullname()
    {
        return $this -> first_name.' '.$this -> surname;
    }

    public function getUserByUsername( $username )
    {
        return $this->where('username', '=', $username)->first();
    }

    public function avatar(){
        return $this -> avatar ? : '/assets/images/user_blank.jpg';
    }

    public function joined()
    {
        return \String::date(Carbon::createFromFormat('Y-n-j G:i:s', $this->created_at));
    }

    public function currentRoleIds()
    {
        $roles = $this->roles;
        $roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as &$role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;
    }

    public static function checkAuthAndRedirect($redirect, $ifValid=false)
    {
        $user = Auth::user();
        $redirectTo = false;

        if(empty($user->id) && ! $ifValid) // Not logged in redirect, set session.
        {
            Session::put('loginRedirect', $redirect);
            $redirectTo = Redirect::to('user/login')
                ->with( 'notice', Lang::get('user/user.login_first') );
        }
        elseif(!empty($user->id) && $ifValid) // Valid user, we want to redirect.
        {
            $redirectTo = Redirect::to($redirect);
        }

        return array($user, $redirectTo);
    }

    public function currentUser()
    {
        $user = \Confide::user();
        $user -> role = $this->userRole;
        return $user;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getUserSection()
    {
        return'/';
    }

    public function getUserRoleName()
    {
        $roles =  $this -> roles;
        $names = '';
        foreach($roles as $role){
            $names .= \Lang::get('/common/roles.'.$role -> name).' ';
        }
        return $names;
    }

    public function getUsersIdByRoles($value){

         $rolesIds = \User::select('id')->whereHas(
            'roles', function($q) use($value){
                $q->where('name', $value);
            }
        )->get()->toArray();
        $out = [];
        foreach($rolesIds as $val){
            $out[] = $val['id'];
        }
        return $out;
    }

    public function getHqManagers($id_hq, $all = false)
    {
        $rolesIds = $this->getUsersIdByRoles('hq-manager');
        if($rolesIds){
            $hqUser = \User::where('headquarters_id','=',$id_hq)->whereIn('id',$rolesIds);
            if(!$all)
                return $hqUser -> where('hq_manager','=',1) -> first();
            else
                return $hqUser -> get();
        }
        return null;
    }


    public function getUsersType($hq,$unit,$role){
        $target = null;
        if($role){
            switch ($role){
                case 'hq'      : $target = 'hq-manager'; break;
                case 'local'   : $target = 'local-manager'; break;
                case 'visitor' : $target = 'visitor'; break;
                default        : $target = null; break;
            }
        }
        if($target)
        {
            $model = $unit?$unit:$hq;
            $users = $model->users()->whereHas(
                'roles', function($q) use($target){
                    $q->where('name', $target);
                }
            )->get();
        }
        return isset($users) ? $users : null;
    }

    public function getAllManagers($id_hq)
    {
        return \User::where('headquarters_id','=',$id_hq)->get();
    }

    public function unitId()
    {
        return $this->unit()->id;
    }

    public function checkAccess()
    {
        $user = $this -> currentUser();
        if ( $user -> hasRole('local-manager') || $user -> hasRole('visitor') )
            return $this -> id == $user -> id;
        elseif ( $user -> hasRole('hq-manager') ) {
            return in_array($this -> unit() -> id, $user -> headquarter() -> getUnitsId());
        }
        else
            return true; //as admin
    }

    public function options (){
        return $this->hasMany('\Model\Options', 'target_id')->whereTargetType($this->getTable());
    }

    public function hasOption( $identifier )
    {
        $options = $this -> options() -> get();
        if($options->count())
            foreach ($options as $option) {
                if( $option->option->identifier == $identifier )
                {
                    return true;
                }
            }
        return false;
    }

    public function getNotifications($type='unread')
    {
        $user = \Auth::user();
        $notifications = \Model\Notifications::whereRaw('find_in_set(?, `receivers_id`)', [$user->id])->orderBy('created_at','DESC')->get();

        return $notifications->filter(function($item)use($type){
            $log = $item -> userLog;
            if(!$log || (((($type == 'unread') && !$log->read) || ($type == 'notremoved') ) && !$log->removed)) {
                return $item;
            }
        });
    }

}
