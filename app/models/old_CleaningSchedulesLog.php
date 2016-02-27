<?php namespace Model;

class CleaningSchedulesLog extends Models {

    protected $table = 'cleaning_schedules_log';
    protected $fillable = [
        'unit_id',
        'task_id',
        'completed',
        'summary',
        'start',
        'end',
        'title',
        'description',
        'staff_name',
        'form_name',
    ];

    public $rules = [
        'task_id'   => 'required|numeric',
        'summary'   => 'required|max:256',
        'completed' => 'required',
        'start'     => 'required|numeric',
        'end'       => 'required|numeric',
    ];

    public function task()
    {
        return $this->belongsTo('\Model\CleaningSchedules', 'task_id');
    }

    public function outstandingTask()
    {
        return $this->belongsTo('\Model\OutstandingTask','id','target_id')->whereTargetType('cleaning_schedules_log');
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' :
            case 'section' : $url = '/cleaning-schedule'; break;
            default : $url = ''; break;
        }
        return $url ;
    }
    public function getOutstandingTaskItemTitle($details = NULL)
    {
        $task = $this -> task;
        if($task) {
            $staff = $task->staff;
            $staffName = $staff ? ' (' . $staff->fullname() . ')' : '';
            return
                parent::getOutstandingTaskItemTitle($details) . '<br>' .
                $task->title . $staffName;
        }else{
            $this->delete();
        }
    }
}