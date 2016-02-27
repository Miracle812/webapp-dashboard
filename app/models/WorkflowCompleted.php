<?php namespace Model;

class WorkflowCompleted extends Models
{
    protected $table  = 'workflow_completed';
    protected $fillable = ['task_id','title','description','date','site_id','site','author_id','author','officer_id','officer','summary','completed','status','tz'];
}