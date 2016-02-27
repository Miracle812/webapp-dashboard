<?php namespace Model;

class OutstandingTask extends Models {

    protected $table = 'outstanding_task';

	protected $fillable = [
		'status',
		'action_todo',
		'trends',
		'assigned',
		'expiry_date',
	];


    public function save(array $options = [])
    {
        $save = parent::save($options);
        if($save){
            \Services\Scores::scoresUpdateByTable($this->target_type);
        }
        return $this;
    }

	public function delete()
	{
		// Delete the comments
		//$this->comments()->delete();

		// Delete the blog post
		return parent::delete();
	}

	public function todo()
	{
		return nl2br($this->todo);
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

	public function getActionButton()
	{
		return '<div class="btn btn-success btn-sm" data-toggle="ajaxModal" data-remote="/outstanding-tasks/resolve/' . $this -> id.'">'.\Lang::get('/common/button.resolve').'</div>';
	}

	public function getSectionName()
	{
		$target = $this -> target();
		return '<a class="btn btn-primary btn-sm" href="'.$target->getUrl('section').'">'. $target -> getSectionName() .'</a>';
	}

	public function getLinkedTitle()
	{
		$target = $this -> target();
		return $target -> getOutstandingTaskItemTitle();
	}
}
