<?php namespace Model;

class HealthQuestionnaires extends Models {

    protected $fillable = [
        'staff_id',
        'type',
        'date',
        'checkbox_1',
        'checkbox_2',
        'checkbox_3',
        'checkbox_4',
        'signature'
    ];

    protected $section_url = '/health-questionnaires/';

    public function staff()
    {
        return $this->belongsTo('\Model\Staffs', 'staff_id');
    }

    public function files()
    {
        return $this->hasMany('\Model\Files', 'target_id')->where('target_type','health-questionnaires');
    }

    public function repository()
    {
        return \App::make('\Repositories\HealthQuestionnaires', [$this]);
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = 'details/'.$this->id; break;
            case 'section' : $url = '' ; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {
        return
            parent::getOutstandingTaskItemTitle($details) . '<br>' .
            $this -> staff -> fullname();
    }
}