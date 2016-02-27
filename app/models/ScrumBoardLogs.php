<?php namespace Model;

class ScrumBoardLogs extends Models {

    protected $fillable = ['user_id','item_id','message','action'];

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function author()
    {
        return $this->user();
    }

	public function item()
	{
		return $this->belongsTo('\Model\ScrumBoardItems', 'item_id');
	}

    public function fullmessage()
	{
        return $this->user->fullname().' '.$this->action.'d <b>'.$this->item->title.'</b> '.$this->message;
	}

}