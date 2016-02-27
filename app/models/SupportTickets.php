<?php namespace Model;

class SupportTickets extends Models {

    protected $fillable = [
        'ident',
        'category_id',
        'user_id',
        'user_name',
        'user_email',
        'title',
        'message',
        'status'
    ];

    public $statuses = [
        'open',
        'answer',
        'close',
    ];

    public function replies()
    {
        return $this->hasMany('\Model\SupportReplies','ticket_id');
    }

    public function category()
    {
        return $this->belongsTo('\Model\SupportCategories', 'category_id');
    }

    public function ident()
    {
        return \Carbon::now()->format('Ymd').'-'.\Auth::user()->id.'-'.$this->id;
    }

    public function author()
    {
        return $this -> belongsTo('\User','user_id');
    }

    public function recipients()
    {
        $recipients = $this->category->members ? $this->category->members->lists('id') : [];
        $recipients = $recipients + [$this->user_id] + \User::whereHas('roles',function($q){$q->where('name','admin');})->lists('id');
        return  $recipients;
    }

    public function imAuthor()
    {
        return ($this -> author -> id == \Auth::user() -> id);
    }

}