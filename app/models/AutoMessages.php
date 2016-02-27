<?php namespace Model;

class AutoMessages extends Models {

    protected $fillable = [
        'group_id',
        'title',
        'message',
        'sort'
    ];

    public function group()
    {
        return $this->belongsTo('\Model\AutoMessagesGroups', 'group_id');
    }
}