<?php namespace Services;

class ObjectProcessor extends \BaseController
{
    public $tables = [
        'health_questionnaires',
        'training_records',
        'cleaning_schedules_items',
        'check_list_items',
        'food_incidents',
        'staff',
        'users',
        'temperatures_for_goods_in',
        'temperatures_for_pods',
        'temperatures_for_probes',
    ];

    public static function afterCreate($object)
    {
        $self = new self();
        if(!in_array(($table = $object->getTable()),$self->tables)){
            return null;
        }
        if(($repo = $object -> repository()) && $repo -> isInvalid()) {
            //foreach (['Scores', 'OutstandingTasks'] as $service) {
            foreach (['Scores'] as $service) {
                $service = '\Services\\' . $service;
                $service::init($object);
            }
        }
    }

    public static function afterUpdate($object)
    {
        $self = new self();
        if(!in_array(($table = $object->table),$self->tables)){
            return null;
        }
        foreach (['OutstandingTasks'] as $service){
            $service = '\Services\\'.$service;
            $service::update($object);
        }
    }
}
