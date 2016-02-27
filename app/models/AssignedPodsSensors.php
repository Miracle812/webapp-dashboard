<?php namespace Model;

class AssignedPodsSensors extends Models {

    public function pod() {
        return $this -> belongsTo('\TemperaturesPodsSensors','pod_id');
    }
    public function area() {
        return $this -> belongsTo('\Model\TemperaturesPodsAreas', 'area_id');
    }
}