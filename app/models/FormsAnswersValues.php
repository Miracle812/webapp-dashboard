<?php namespace Model;

class FormsAnswersValues extends Models {

    protected $fillable = ['unit_id','answer_id','item_log_id','value'];

    public function itemLog()
    {
        return $this->belongsTo('\Model\FormsItemsLogs', 'item_log_id');
    }

    public function answer()
    {
        return $this->belongsTo('\Model\FormsAnswers', 'answer_id');
    }
}