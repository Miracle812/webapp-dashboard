<?php namespace Model;

class TemperaturesForCalibration extends TemperaturesModel {
    var $table = 'temperatures_for_calibration';
    public function probe()
    {
        return $this->belongsTo('\Model\ProbesDevices', 'device_identifier','device_id');
    }
}