<?php namespace Model;

class WorkflowLogs extends Models {

    protected $fillable = ['user_id','task_id','item_id','site_id','message','action'];

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function site()
    {
        return $this->belongsTo('\Model\Units', 'site_id');
    }

    public function item()
	{
		return $this->belongsTo('\Model\WorkflowItems', 'item_id');
	}

    public function task()
	{
		return $this->belongsTo('\Model\WorkflowTasks', 'task_id');
	}

    public function fullmessage()
	{
        return $this->user->fullname().' '.$this->action.'d <b>'.$this->task->title.'</b> '.$this->message;
	}
}