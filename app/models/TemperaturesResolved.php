<?php namespace Model;

class TemperaturesResolved extends Models
{
    protected $table = 'temperatures_resolved';
    public $rules = [
        'unit_id' => 'required',
        'comment' => 'required',
        'resolved' => 'required',
    ];
    protected $fillable = ['unit_id', 'comment', 'resolved'];
    public function site()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}