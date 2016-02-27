<?php namespace Model;

class Headquarters extends Models {

    protected $fillable = [
        'name',
        'city',
        'street_number',
        'post_code',
        'email',
        'phone',
        'mobile_phone',
        'gmap_lat',
        'gmap_lng',
        'gmap_zoom',
    ];

    public $rules = [
        'name'      => 'required|min:5',
        'post_code' => 'required',
        'city'      => 'required',
        'street_number'=> 'required',
        'email'     => 'required|email|unique:headquarters',
        'mobile_phone' => 'required',
        'gmap_lat'  => 'required',
        'gmap_lng'  => 'required',
        'gmap_zoom' => 'required',
    ];

    public function delete ()
    {
        $this -> units() -> delete();
        $this -> users() -> delete();
        return parent::delete();
    }

    public function units (){
        return $this->hasMany('\Model\Units','headquarter_id');
    }

    public function users (){
        return $this->belongsToMany('\User', 'assigned_headquarters', 'headquarter_id', 'user_id');
    }

    public function getUsersByRole($role){
        $users = $this->users()->whereHas(
            'roles', function($q) use($role){
                $q->where('name', $role);
            }
        ) -> get();
        return $users;
    }

    public function hqManagers(){
        $user = \Auth::user();
        return $this -> users() -> whereHas(
            'roles', function($q) use($user) {
                $q->whereName('hq-manager')->where('user_id','<>',$user->id);
            });
    }

    public function localManagers(){
        $user = \Auth::user();
        return $this -> users() -> whereHas(
            'roles', function($q) use($user) {
            $q->whereName('local-manager')->where('user_id','<>',$user->id);
        });
    }

    public function getUnitsId(){
        return $this->units()->lists('id');
    }
}