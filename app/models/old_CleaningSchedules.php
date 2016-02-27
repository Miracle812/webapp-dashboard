<?php namespace Model;

class CleaningSchedules extends Models {

    protected $fillable = [
        'staff_id',
        'form_id',
        'repeat',
        'repeat_freq',
        'title',
        'description',
        'all_day',
        'type',
        'start',
        'end',
        'from',
        'to',
        'weekend',
    ];

    public $rules = [
        'repeat'      => 'required',
        'start'       => 'required',
        'end'         => 'required',
        'title'       => 'required|min:3',
        'description' => 'required|min:3',
        'type'        => 'required',
        'all_day'     => 'required',
    ];

    public function staff(){
        return $this->belongsTo('\Model\Staffs', 'staff_id');
    }
    public function unit(){
        return $this->belongsTo('\Model\Units', 'unit_id');
    }
    public function form(){
        return $this->belongsTo('\Model\Forms', 'form_id');
    }

    public function scheduleLogs(){
        return $this->hasMany('\Model\CleaningSchedulesLog', 'task_id');
    }

    public function outstandingTasks()
    {
        return \Model\OutstandingTask::whereIn('target_id',$this->scheduleLogs()->lists('id'))->whereTargetType('cleaning_schedules_log');
    }
}