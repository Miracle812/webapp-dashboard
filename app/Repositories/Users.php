<?php
namespace Repositories;
class Users
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
            'message'       => 'If a Site Manager has not logged into the system for longer than 7 days',
        ];
    }
}