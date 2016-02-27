<?php namespace Model;

class NotificationsLogs extends Models
{
    public $fillable = [
        'user_id',
        'notification_id',
        'read',
        'removed'
    ];
    public function user()
    {
        return $this->belongsTo('\User', 'user_id');
    }

    public function notification()
    {
        return $this->belongsTo('\Model\Notifications', 'notification_id');
    }

}