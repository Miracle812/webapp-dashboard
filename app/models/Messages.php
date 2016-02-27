<?php namespace Model;

use Illuminate\Support\Collection;

class Messages extends Models {

    protected $fillable = ['thread_id','message','author_id'];

    public $rules = [ 'message'=>'required','author_id'=>'required'];

    public function author()
    {
        return $this -> belongsTo('\User','author_id');
    }

    public function recipients()
    {
        return $this->belongsToMany('\User', 'messages_recipients', 'message_id', 'user_id');
    }

    public function imAuthor()
    {
        return ($this -> author -> id == \Auth::user() -> id);
    }

    public function parent()
    {
        return $this->belongsTo('\Model\Messages','thread_id','id');
    }

    public function childs()
    {
        return $this->hasMany('\Model\Messages', 'thread_id','id');
    }

    public function allMessages()
    {
        if($this->thread_id > 0)
            return \Model\Messages::where('id',$this->thread_id)->orWhere('thread_id',$this->thread_id)->get();
        else
            return \Model\Messages::where('id',$this->id)->orWhere('thread_id',$this->id)->get();
    }
}