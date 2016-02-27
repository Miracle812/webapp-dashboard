<?php namespace Model;

class NonCompliantTrends extends Models
{
    protected $table = 'noncompliant_trends';
    protected $fillable = [
        'name',
        'unit_id',
        'sort'
    ];

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
}