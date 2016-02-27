<?php namespace Model;

class AutoMessagesTasks extends Models {
    public $timestamps = false;
    protected $fillable = [
        'message_id',
        'target_type',
        'target_id',
        'trigger_date',
        'on_email',
        'on_sms',
    ];

    public function message()
    {
        return $this->belongsTo('\Model\AutoMessages', 'message_id');
    }
}