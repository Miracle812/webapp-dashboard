<?php
namespace Repositories;
class FoodIncidents
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return !($this -> object -> status);
    }

    public function getScoresData()
    {
        $object = $this -> object;
        if($this-> isInvalid()) {
            return [
                'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
                'value'         => \Config::get('scores.sections.'.$table.'.value'),
                'message'       => 'Incidents occuring but not resolved.',
            ];
        };
        return [];
    }
}