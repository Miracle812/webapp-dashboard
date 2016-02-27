<?php namespace Model;

class Scores extends Models {

    protected $fillable = [ 'unit_id','target_type','target_id','name','value','type','scores','message'];

    public function task()
    {
        return $this->belongsTo('\Model\OutstandingTask', 'out_task_id');
    }

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}