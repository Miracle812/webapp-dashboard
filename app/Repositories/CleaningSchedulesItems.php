<?php
namespace Repositories;
class CleaningSchedulesItems
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return !($this -> object -> isCompleted());
    }

    public function getScoresData()
    {
        $object = $this -> object;
        if($message = $this-> isInvalid()) {
            return [
                'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
                'value'         => \Config::get('scores.sections.'.$table.'.value'),
                'message'       => 'More than 5 Cleaning Schedules remaining red within the last month',
            ];
        };
        return [];
    }
}