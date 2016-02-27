<?php namespace Model;

class AssignedUnits extends Models {

    public function user() {
        return $this -> belongsTo('\User');
    }
    public function unit() {
        return $this -> belongsTo('\Model\Units');
    }
}