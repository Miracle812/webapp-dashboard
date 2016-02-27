<?php namespace Model;

class Options extends Models
{
    protected $fillable = ['option_id','target_id','target_type'];

    public function option()
    {
        return $this->belongsTo('\Model\OptionsMenu', 'option_id');
    }
}
