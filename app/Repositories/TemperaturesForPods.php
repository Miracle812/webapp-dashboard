<?php
namespace Repositories;
class TemperaturesForPods
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return (!is_null($this -> object -> invalid_id) && ($this -> object -> invalid_id > 0));
    }

    public function getScoresData()
    {
        $object = $this -> object;
        if($this-> isInvalid()) {
            return [
                'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
                'value'         => \Config::get('scores.sections.'.$table.'.value'),
                'message'       => 'More than 5 non compliant temperatures within a 7 day period',
            ];
        };
        return [];
    }
}