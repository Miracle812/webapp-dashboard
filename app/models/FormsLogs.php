<?php namespace Model;

class FormsLogs extends Models
{
    protected $fillable = ['unit_id', 'assigned_id','name','description','group'];
    public function items_log()
    {
        return $this->hasMany('\Model\FormsItemsLogs', 'form_log_id');
    }

    public function assigned_section()
    {
        $formsRepo = \App::make('FormsRepository');
        return $formsRepo->assigned[$this->assigned_id];
    }
}