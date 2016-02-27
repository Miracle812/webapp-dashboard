<?php
namespace Repositories;
class Staffs
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return true;
    }

    public function getScoresData()
    {
        $object = $this -> object;
        return [
            'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
            'value'         => \Config::get('scores.sections.'.$table.'.value'),
            'message'       => 'If a new member of staff is registered on the system and no Health Questionnaire has been completed within 7 days',
        ];
    }
}