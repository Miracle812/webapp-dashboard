<?php namespace Model;

class Units extends Models {

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
        'email'     => 'required|email|unique:units',
        'mobile_phone' => 'required',
        'gmap_lat'  => 'required',
        'gmap_lng'  => 'required',
        'gmap_zoom' => 'required',
    ];

    public function delete ()
    {
        $unitUsers  = $this->users();
        $unitUsers -> delete();
        return parent::delete();
    }

    public function repository()
    {
        return \App::make('\Repositories\Units', [$this]);
    }

    public function headquarter (){
        return $this->belongsTo('\Model\Headquarters', 'headquarter_id');
    }

    public function users (){
        return $this->belongsToMany('\User', 'assigned_units', 'unit_id', 'user_id');
    }

    public function suppliers (){
        return $this->hasMany('\Model\Suppliers', 'unit_id');
    }

    public function unitsContacts (){
        return $this->hasMany('\Model\UnitsContacts', 'unit_id');
    }

    public function ratings (){
        return $this->hasMany('\Model\RatingStarsLogs', 'unit_id');
    }

    public function rating ()
    {
        return $this->ratings()->whereRaw('id IN (SELECT max(id) FROM rating_stars_logs GROUP BY unit_id)')->first();
    }

    public function getUsersByRole($role)
    {
        return $this->users()->whereHas(
            'roles', function($q) use($role){
                $q->where('name', $role);
            }
        ) -> get();
    }

    public function getEndDisabledModules()
    {
        $list = $this->getDisabledModules();
        if(!$list)
            $list = $this -> headquarter -> getDisabledModules();
        return is_array($list) ? $list : [];
    }

    public function checkAccess()
    {
        $user = \Auth::user();
        if ( $user -> hasRole('local-manager') || $user -> hasRole('visitor') || $user -> hasRole('area-manager') )
            return in_array($this -> id,  $user -> units -> lists('id'));
        elseif ( $user -> hasRole('hq-manager') ) {
            return in_array($this -> id, $user -> headquarter() -> getUnitsId());
        }
        elseif ( $user -> hasRole('admin') ) {
            return true;
        }
        return false;
    }
}