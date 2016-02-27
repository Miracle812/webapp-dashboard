<?php namespace Model;

class WorkflowItems extends Models
{
    public $timestamps = false;
    protected $fillable = [
        'site_id',
        'task_id',
        'user_id',
        'date',
        'status',
    ];
    public function task()
    {
        return $this->belongsTo('\Model\WorkflowTasks', 'task_id');
    }

    public function user()
    {
        return $this->belongsTo('\User', 'user_id');
    }

    public function site()
    {
        return $this->belongsTo('\Model\Units', 'site_id');
    }

    public function timelines()
    {
        return $this->hasMany('\Model\WorkflowLogs', 'item_id');
    }
}