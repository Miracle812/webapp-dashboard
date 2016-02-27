<?php namespace Model;

class CheckListSubmitted extends Models
{
    protected $table    = 'check_list_submitted';
    protected $fillable = ['unit_id','task_item_id','task_id','form_answer_id','completed','staff_name','form_name','title','description','summary','start','end','task_color','expired_dates','all_day','tz'];
    public $rules = [
        'summary'      => 'required|max:256',
        'completed'    => 'required'];

    public function item()
    {
        return $this -> belongsTo('\Model\CheckListItems', 'task_item_id');
    }

    public function task()
    {
        return $this -> item ? $this -> item -> task : null;
    }

    public function formAnswer()
    {
        return $this -> belongsTo('\Model\FormsAnswers', 'form_answer_id');
    }
}