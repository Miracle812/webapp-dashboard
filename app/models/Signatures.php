<?php namespace Model;

class Signatures extends Models {

    protected $fillable = ['user_id', 'unit_id', 'signature', 'name', 'role'];
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}