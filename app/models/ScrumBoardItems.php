<?php namespace Model;

class ScrumBoardItems extends Models {

    protected $fillable = ['title','description', 'user_id', 'ident','list','priority','sort'];
    public $columns = [1=>'To do',2=>'Analysts',3=>'In progress',4=>'Done'];

	public function description()
	{
		return nl2br($this->content);
	}

    public function columnName($index = null)
    {
        return $this->columns[ ($index ? : $this->list) ];
    }

	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

    public function user()
    {
        return $this->author();
    }
}
