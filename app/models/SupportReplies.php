<?php namespace Model;

class SupportReplies extends Models {

    protected $fillable = [
        'ticket_id',
        'ticket_id',
        'user_id',
        'user_name',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo('\Model\SupportTickets', 'ticket_id');
    }

    public function author()
    {
        return $this -> belongsTo('\User','user_id');
    }

    public function imAuthor()
    {
        return ($this -> author -> id == \Auth::user() -> id);
    }
}