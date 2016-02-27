<?php namespace Model;

class ComplianceDiaryTasks extends Models
{
    protected $fillable = [
        'title',
        'description',
        'staff_id',
        'unit_id',
        'form_id',
        'all_day',
        'start',
        'end',
        'is_repeatable',
        'repeat',
        'repeat_every',
        'repeat_until',
        'weekends',
        'task_color',
        'tz'
    ];
    public $rules = [
        'repeat'      => 'required',
        'start'       => 'required',
        'end'         => 'required',
        'title'       => 'required|min:3',
        'description' => 'required|min:3',
        'type'        => 'required',
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
    public function items(){
        return $this->hasMany('\Model\ComplianceDiaryItems', 'task_id');
    }
}