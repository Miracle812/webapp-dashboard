<?php namespace Model;

class TemperaturesPodsAreas extends NestableTree {

    protected $table = 'temperatures_pods_areas';

    protected $fillable = [
        'root',
        'parent_id',
        'unit_id',
        'type',
        'name',
        'description',
        'rule_description',
        'lang',
        'warning_min',
        'warning_max',
        'valid_min',
        'valid_max',
    ];
    public $rules = [
        'group' => [
            'name'=> 'required|min:5|max:255'
        ],
        'area'  => [
            'name'             => 'required|min:5|max:255',
            'rule_description' => 'required|max:255',
            'type'             => 'required',
            'valid_min'  => 'required|numeric|between:-50,150',
            'valid_max'  => 'required|numeric|between:-50,150',
        ],
    ];

    public $group = 'pods';

    public function delete()
    {
        \Model\TemperaturesAlertBox::whereAreaId($this->id)->whereGroup($this->group)->delete();
        return parent::delete();
    }

    public function excludeTimeframe()
    {
        return $this->belongsTo('\Model\TemperaturesPodsTimeExclude','id','area_id');
    }

    public function pods()
    {
        return $this->belongsToMany('\Model\TemperaturesPodsSensors', 'assigned_pods_sensors','pod_area_id','pod_sensor_id');
    }

    public function parent()
    {
        return $this->belongsTo('\Model\TemperaturesPodsAreas','parent_id');
    }

    public function savePods(array $podsIds)
    {
        if(!empty($podsIds)) {
            \Model\AssignedPodsSensors::whereIn('pod_sensor_id',$podsIds)->delete();
            $this->pods()->sync($podsIds);
        } else {
            $this->pods()->detach();
        }
    }

    public function getTreeFromDB($roles = null)
    {
        $root = $this->getRoot($roles);
        if(!$root){
            $root = $this->createRoot($roles);
        }
        $entities = $this::
        whereLang($root -> lang) ->
        whereUnitId($root -> unit_id) ->
        orderBy('sort', 'ASC') ->
        get();
        $groups = $this -> _groupByParents($entities);
        return $this -> _makeTree($groups);
    }

    public function getRoot($roles = null)
    {
        $user = \Auth::user();
        return $this ->
        whereLang($user -> lang) ->
        whereUnitId($user -> unitId()) ->
        where('name', '=', 'ROOT') ->
        where('type', '=', 'ROOT') ->
        first();
    }

    public function createRoot($roles = null)
    {
        $user = \Auth::user();
        $this -> name = 'ROOT';
        $this -> type = 'ROOT';
        $this -> parent_id = 0;
        $this -> unit_id   = $user -> unitId();
        $this -> lang      = $user -> lang;
        $this -> save();
        return $this;
    }

    public function temperatures()
    {
        return $this->hasMany('\Model\TemperaturesForPods', 'area_id');
    }

    public function getLastTemperature()
    {
        $temperatures = $this -> temperatures();
        $temperatures = $temperatures -> count() ? $temperatures : false;
        return $temperatures ? $temperatures->whereRaw('id IN (SELECT max(id) FROM temperatures_for_pods GROUP BY area_id)')->first() : null;
    }

    public function getLastTempToday()
    {
        return $this -> temperatures() -> where('created_at', '>', \Carbon::today()) -> where('created_at', '<', \Carbon::now())->orderBy('created_at','DESC')->first();
    }

    public function getTodayTemperatures($limit = 10)
    {
        return $this -> temperatures() -> where('created_at', '>', \Carbon::today()) -> where('created_at', '<', \Carbon::now())->orderBy('created_at','desc')->take($limit)->get();
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = '/item/'.$this->id; break;
            case 'section' :  $url = ''; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function checkAccess()
    {
        $user = \Auth::user();
        if ( $user -> hasRole('local-manager') || $user -> hasRole('visitor') )
            return $this -> unit_id == $user -> unit() -> id;
        elseif ( $user -> hasRole('hq-manager') ) {
            return $this -> unit_id == $user -> headquarter() -> id;
        }
        else
            return true; //as admin
    }

    public function getNavitasMessage()
    {
        $area = $this;
        $message = $area->rule_description;
        preg_match_all ('/{([^{]+?)}/', $message, $matches);
        foreach ($matches[0] as $key => $match){

            $smallTag = strtolower($matches[1][$key]);
            if(isset($this->defaultPlaceholder[$smallTag]))
                $value = $this->defaultPlaceholder[$smallTag];
            else
                $value = '<b>'.$area->{$smallTag}.'</b>';

            $message = str_replace($match, $value, $message);
        }
        return $message;
    }

    public function getParentsNames($item = null, $arr = [])
    {
        $item = $item ? : $this;
        $parent = $item -> parent;
        return ($parent && $parent->lvl) ? $item->getParentsNames($parent,array_merge([ $parent -> name ],$arr)) : $arr;
    }
}