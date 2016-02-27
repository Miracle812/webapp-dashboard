<?php namespace Model;

class WorkflowTasks extends Models
{
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'priority',
        'contact_type',
        'assigned_sites',
        'assigned_officers',
        'target',
        'repeat',
        'frequency',
        'weekend',
        'tz'
    ];
    public function items()
    {
        return $this->belongsToMany('\Model\WorkflowItems', 'task_id');
    }

    public function assigned_sites()
    {
        $this -> assigned_sites = explode(',',$this->assigned_sites);
        return $this->belongsTo('\Model\Units', 'assigned_sites');
    }

    public function assigned_officers()
    {
        $this -> assigned_officers = explode(',',$this->assigned_officers);
        return $this->belongsTo('\User', 'assigned_officers');
    }

    public function author()
    {
        return $this->belongsTo('\User', 'author_id');
    }
}