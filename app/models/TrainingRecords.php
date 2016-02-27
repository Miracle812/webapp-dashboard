<?php namespace Model;

class TrainingRecords extends Models {

    protected $fillable = [
        'staff_id',
        'name',
        'address',
        'comments',
        'date_start',
        'date_finish',
        'date_refresh'
    ];

    public function save(array $options = [])
    {
        $save = parent::save($options);
        if($save){
            \Services\ObjectProcessor::afterCreate($this);
        }
        return $this;
    }

    public function units()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function files()
    {
        return $this->hasMany('\Model\Files', 'target_id')->where('target_type','=','trainings');
    }

    public function staff()
    {
        return $this->belongsTo('\Model\Staffs', 'staff_id');
    }

    public function date_start()
    {
        return $this->date($this->date_start);
    }

    public function date_refresh()
    {
        return $this->date($this->date_refresh);
    }

    public function repository()
    {
        return \App::make('\Repositories\TrainingRecords', [$this]);
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = 'details/'.$this->id; break;
            case 'section' : $url = 'list/'.$this->staff->id; break;
            default : $url = ''; break;
        }
        return '/trainings/' . $url ;
    }

    public function getSectionName()
    {
        $table = $this -> getTable();
        return \Lang::get('/common/sections.'.$table.'.title');
    }

    public function getOutstandingTitle()
    {
        $table = $this -> getTable();

        $title2 = \Lang::get('/common/sections.'.$table.'.messages.outstanding_tasks');

        return  $title2;
    }

    public function getOutstandingTaskItemTitle($details = null)
    {
        $details = \Lang::get('/common/general.staff').': '.$this -> staff -> fullname().', '.\Lang::get('/common/general.training').': '.$this -> name;
        return
            parent::getOutstandingTaskItemTitle() . '<br>' .
            $details;
    }
}