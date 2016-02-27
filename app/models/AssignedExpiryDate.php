<?php namespace Model;

class AssignedExpiryDate extends Models {

    public function user() {
        return $this -> belongsTo('\User');
    }
}