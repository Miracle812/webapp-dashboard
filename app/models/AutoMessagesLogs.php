<?php namespace Model;

class AutoMessagesLogs extends Models {
    public $timestamps = false;
    protected $fillable = [
        'message_id',
        'target_type',
        'target_id',
        'trigger_date',
        'on_email',
        'on_sms',
    ];
}