<?php namespace Model;

class TemperaturesPodsSensors extends Models {

    protected $fillable = ['identifier','name','description','unit_id'];

    public function areas()
    {
        return $this->belongsToMany('\Model\TemperaturesPodsAreas', 'assigned_pods_sensors','pod_sensor_id','pod_area_id');
    }
    public function area()
    {
        return $this->areas()->first();
    }
}