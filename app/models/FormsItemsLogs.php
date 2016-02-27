<?php namespace Model;

class FormsItemsLogs extends Models
{
    protected $fillable = ['form_log_id','parent_id','org_id','label','description','type','sort','options','required'];

    public function formLog()
    {
        return $this->belongsTo('\Model\FormsLogs', 'form_log_id');
    }
}