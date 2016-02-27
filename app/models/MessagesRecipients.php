<?php namespace Model;

class MessagesRecipients extends Models
{
    public $timestamps = false;
    public $fillable = ['status','message_id', 'user_id'];
    public function message(){
        return $this -> belongsTo('\Model\Messages', 'message_id');
    }
    public function user(){
        return $this -> belongsTo('\User', 'user_id');
    }
}