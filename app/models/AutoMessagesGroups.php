<?php namespace Model;

class AutoMessagesGroups extends Models
{
    protected $fillable = [
        'name',
        'target_type',
        'weekends',
        'freq_type',
        'freq_value',
        'delay_type',
        'delay_value',
        'send_hour',
        'active',
        'on_email',
        'on_sms',
    ];

    public function messages()
    {
        return $this->hasMany('\Model\AutoMessages', 'group_id');
    }
}