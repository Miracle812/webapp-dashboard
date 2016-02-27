<?php namespace Model;

class TemperaturesForPods extends TemperaturesModel
{
    public $rules = [
        'temperature' => 'required|numeric|between:-50,100',
        //'battery_voltage'  => 'required|numeric|between:0,12',
        //'battery_level'  => 'required|numeric|between:0,100',
        'pod_id'      => 'required|integer',
    ];

    protected $fillable = [
        'battery_level',
        'battery_voltage',
        'temperature',
        'pod_name',
        'area_id',
        'unit_id',
        'pod_id',
        'rules_id',
        'invalid_id',
        'hub_log_id',
        'pod_ident',
        'timestamp',
        'created_at',
        'updated_at',
    ];


    public function pod()
    {
        return $this->belongsTo('\Model\TemperaturesPodsSensors', 'pod_id');
    }

    public function area()
    {
        return $this->belongsTo('\Model\TemperaturesPodsAreas', 'area_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\TemperaturesForPods', [$this]);
    }

    public function save(array $options = [])
    {
        $save = parent::save($options);
        if($save && $this->invalid_id){
            \Services\AutoMessages::create($this);
        }
        return $this;
    }
}