<?php namespace Model;

class TemperaturesProbesAreas extends Models {

    protected $fillable = [
        'name',
        'description',
        'rule_description',
        'warning_min',
        'warning_max',
        'valid_min',
        'valid_max',
    ];

    public $rules = [
        'name'             => 'required',
        'rule_description' => 'required',
        'warning_min' => 'required|numeric|between:-50,150',
        'warning_max' => 'required|numeric|between:-50,150',
        'valid_min' => 'required|numeric|between:-50,150',
        'valid_max' => 'required|numeric|between:-50,150',
    ];
    public $group = 'probes';
    private $defaultPlaceholder = ['celsius'=>'&#x2103'];

    public function delete()
    {
        \Model\TemperaturesAlertBox::whereAreaId($this->id)->whereGroup($this->group)->delete();
        return parent::delete();
    }

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function parent()
    {
        return $this->belongsTo('\Model\TemperaturesProbesAreas', 'parent_id');
    }

    public function children($unitId)
    {
        return $this->hasOne('\Model\TemperaturesProbesAreas', 'parent_id')->whereUnitId($unitId);
    }

    public function temperatures()
    {
        return $this->hasMany('\Model\TemperaturesForProbes', 'area_id');
    }

    public function getLastTemperature()
    {
        $temperatures = $this -> temperatures();
        $temperatures = $temperatures -> count() ? $temperatures : false;
        return $temperatures ? $temperatures->whereRaw('id IN (SELECT max(id) FROM temperatures_for_probes GROUP BY area_id)')->first() : null;
    }

    public function getLastTempToday()
    {
        return $this -> temperatures() -> where('created_at', '>', \Carbon::today()) -> where('created_at', '<', \Carbon::now())->orderBy('created_at','DESC')->first();
    }

    public function getLastTemperatures($limit = 10)
    {
        $temperatures = $this -> temperatures();
        $temperatures = $temperatures -> count() ? $temperatures : false;
        return $temperatures ? $temperatures->orderBy('id','DESC')->take($limit)->get() : null;
    }

    public function getTodayTemperatures($limit = 10)
    {
        return $this -> temperatures() -> where('created_at', '>', \Carbon::today()) -> where('created_at', '<', \Carbon::now())->orderBy('created_at','desc')->take($limit)->get();
    }

    public function isChilling(){
        if($this->unit)
            if($this->hasParent())
                if($this->getParent()->id == 1)
                    return true;
        return false;
    }

    public function getTargetArea($unitId)
    {
        if($this->hasParent())
            return $this;
        if($this->hasChildren($unitId))
            return $this->children($unitId)->first();
        if(!$this->hasParent() && !$this->hasChildren($unitId))
            return null;
    }

    public function hasParent()
    {
        return $this->parent?true:false;
    }

    public function getParent()
    {
        return $this::where('id','=',$this->parent_id)->first();
    }

    public function hasChildren($unitId)
    {
        return $this->children($unitId)->first()?true:false;
    }

    public function createUnitProbeAreas($unit_id)
    {
        $rootAreas = $this -> whereNull('unit_id') -> get();
        foreach($rootAreas as $area)
            $area -> createChild($unit_id);
        return $this -> whereUnitId($unit_id) -> get();
    }

    public function createChild($unitId)
    {
        if($unitId) {
            $new = $this->replicate();
            $new->unit_id = $unitId;
            $new->parent_id = $this->id;
            $new->save();
            return $new;
        }
        return false;
    }

//probes
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
}