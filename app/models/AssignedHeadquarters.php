<?php namespace Model;

class AssignedHeadquarters extends Models {

    public function user() {
        return $this -> belongsTo('\User');
    }
    public function headquarter() {
        return $this -> belongsTo('\Model\Headquarters');
    }
}