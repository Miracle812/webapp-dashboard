<?php namespace Model;

class TemperaturesPodsSensorsNa extends Models {
    protected $table = 'temperatures_pods_sensors_na';
    protected $fillable = ['hub_log_id','pod_ident','battery_level','battery_voltage','temperature','timestamp'];
}