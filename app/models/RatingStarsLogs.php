<?php namespace Model;

class RatingStarsLogs extends Models
{
    protected $fillable = ['stars','unit_id','description'];

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}