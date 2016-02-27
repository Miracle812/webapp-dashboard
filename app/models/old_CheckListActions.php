<?php namespace Model;

class CheckListActions extends Models {

    public $calendar_rules = [
        'comment'      => 'require',
        'assigned'     => 'require',
        'status' => 'require'
    ];

    public $fillable = [
        'action_todo',
        'assigned',
        'status'
    ];

    protected $section_url = '/check-list/';

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function signature()
    {
        return $this->belongsTo('\Model\Signatures', 'signature_id');
    }

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function task()
    {
        return $this->belongsTo('\Model\CheckListTasks', 'task_id');
    }

    public function getSectionName()
    {
        $group  = $this -> getGroup();
        $table  = $this -> getTable();
        return \Lang::get('/common/sections.'.$table.'.'.$group.'.title');
    }

    public function getGroup()
    {
        $task = $this -> task;
        if(!$task)
            return false;
        return  $this -> task -> section -> group == 1 ? 'daily' : 'monthly';
    }

    public function getUrl( $type = 'item' )
    {
        $group = $this -> getGroup();

        switch ($type){
            case 'item' : $url = $group; break;
            case 'section' : $url = 'log-list/' . $group; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {
        return
            parent::getOutstandingTaskItemTitle($details) . '<br>' .
            $this -> task -> content;
    }


}