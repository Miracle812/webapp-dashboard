<?php namespace Model;

class Notifications extends Models
{

    public function user()
    {
        return $this->belongsTo('\User', 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function logs()
    {
        return $this->hasMany('\Model\NotificationsLogs', 'notification_id');
    }

    public function userLog()
    {
        return $this->belongsTo('\Model\NotificationsLogs', 'id', 'notification_id')->where('user_id',\Auth::user()->id);
    }
}