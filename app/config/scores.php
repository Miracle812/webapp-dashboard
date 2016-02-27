<?php

return array(
    'points' => ['start' => 1000, 'max' => 1000, 'min' => 0],
    'sections'=>[
        'cleaning_schedules_items'      => ['value' => -30, 'type' => 'points', 'options' => ['items_val' => 5, 'start_date' => 'startOfMonth', 'items_ope' => '>', 'types'=>['default','danger'] ] ],
        'check_list_items'              => ['value' => -20, 'type' => 'points', 'options' => [] ],
        'food_incidents'                => ['value' => -50, 'type' => 'points', 'options' => [] ],
        'training_records'              => ['value' => -30, 'type' => 'points', 'options' => [] ],
        'health_questionnaires'         => ['value' => -30, 'type' => 'points', 'options' => [] ],
        'staffs'                        => ['value' => -30, 'type' => 'points', 'options' => [] ],
        'users'                         => ['value' => -50, 'type' => 'points', 'options' => [] ],
        'temperatures_for_pods'         => ['value' => -20, 'type' => 'points', 'options' => ['items_val' => 5, 'start_date' => 'startOfWeek', 'items_ope' => '=' ]],
        'temperatures_for_probes'       => ['value' => -20, 'type' => 'points', 'options' => ['items_val' => 5, 'start_date' => 'startOfWeek', 'items_ope' => '=' ]],
        'temperatures_for_goods_in'     => ['value' => -20, 'type' => 'points', 'options' => ['items_val' => 5, 'start_date' => 'startOfWeek', 'items_ope' => '=' ]],
    ],
    'bonus' => [
        'day'   => ['value' => 0, 'type'=>'points'],
        'week'  => ['value' => 0, 'type'=>'points'],
        'month' => ['value' => 0, 'type'=>'points'],
    ],
    'reset' => [
        'month' => ['value' => 1000, 'type'=>'points'],
    ]
);