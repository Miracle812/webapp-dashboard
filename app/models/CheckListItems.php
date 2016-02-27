<?php namespace Model;

class CheckListItems extends Models
{
    public $timestamps = false;
    protected $fillable = [
        'unit_id',
        'task_id',
        'start',
        'end',
    ];

    public $rules = [
        'task_id'   => 'required|numeric',
        'start'     => 'required',
        'end'       => 'required',
    ];

    public function task()
    {
        return $this->belongsTo('\Model\CheckListTasks', 'task_id');
    }

    public function submitted()
    {
        return $this->hasMany('\Model\CheckListSubmitted', 'task_item_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\CheckListItems', [$this]);
    }

    public function getMaxId($collection)
    {
        return $collection->max('id');
    }

    public function isCompleted()
    {
        return ($this->submitted->filter(function($item){
                return $item -> completed ? true : false;
            })->count() > 0) ? 1 : 0;
    }

    public function getCompleted()
    {
        return ($this->submitted()->get()->filter(function($item){
                return $item -> completed ? true : false;
            })->count() > 0) ? 1 : 0;
    }

    public function getLastSubmitted()
    {
        $submitted = $this -> submitted;
        if($submitted -> count())
            if($id = $this->getMaxId($submitted))
                if($record = \Model\CheckListSubmitted::find($id))
                    return $record;
        return null;
    }

    public function getExpiryDate()
    {
        return $this->end ?
            \Carbon::parse($this -> end)->timezone($this->task->tz)->format('Y-m-d') :
            \Carbon::parse($this -> start)->timezone($this->task->tz)->endOfDay()->format('Y-m-d');
    }

    public function isExpired()
    {
        if($this -> end) {
            return ($this->end < \Carbon::now());
        } else{
            return (\Carbon::parse($this->start,"UTC")->timezone($this->task->tz)->endOfDay() < \Carbon::now($this->task->tz));
        }
    }

    public function isForm()
    {
        return ($this -> task -> form) ? true : false;
    }

    public function isAbleToComplete()
    {
        $completed = $this -> isCompleted();
        $expired   = $this -> isExpired();
        $future    = $this -> start > \Carbon::now();
        return (!$completed && !$expired && !$future);
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' :
            case 'section' : $url = '/check-list'; break;
            default : $url = ''; break;
        }
        return $url ;
    }
}