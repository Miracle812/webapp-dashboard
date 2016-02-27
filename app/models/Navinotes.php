<?php namespace Model;

class Navinotes extends Models {

    protected $fillable = [
        'start',
        'end',
        'priority',
        'name',
        'description',
    ];

    protected $section_url = '/navinotes/';

    public function unit (){
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function user (){
        return $this->belongsTo('\User', 'user_id');
    }
    
    public function files()
    {
        return $this->hasMany('\Model\Files', 'target_id');
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
            strtok(wordwrap($this -> name, 25, "..."), "");
    }
}