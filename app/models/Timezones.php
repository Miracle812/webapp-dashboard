<?php namespace Model;

class Timezones extends Models {

    public function user(){
        return $this->belongsTo('\Users', 'timezone', 'identifier');
    }
    public function unit(){
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}