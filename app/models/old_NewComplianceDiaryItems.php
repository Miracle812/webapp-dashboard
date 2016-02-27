<?php namespace Model;

class NewComplianceDiaryItems extends Models
{
    protected $fillable = [
        'unit_id',
        'task_id',
        'start',
        'end',
        'expiry',
    ];

    public $rules = [
        'task_id'   => 'required|numeric',
        'start'     => 'required|numeric',
        'end'       => 'required|numeric',
    ];

    public function task()
    {
        return $this->belongsTo('\Model\NewComplianceDiaryTasks', 'task_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\NewComplianceDiaryItems', [$this]);
    }

    public function getMaxId($collection)
    {
        return $collection->max('id');
    }

    public function isExpired()
    {
       return ($this -> expiry < \Carbon::now()->endOfDay());
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' :
            case 'section' : $url = '/new-compliance-diary'; break;
            default : $url = ''; break;
        }
        return $url ;
    }
}