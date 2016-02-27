<?php namespace Model;

class FormsGroups extends Models
{
    public $timestamps = false;
    protected $fillable = ['assigned_id', 'unit_id', 'name','description'];
}