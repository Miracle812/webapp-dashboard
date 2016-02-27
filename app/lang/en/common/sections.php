<?php

return array(

    'home'                  => 'Home',
    'goods in'              => ['title'=>'Goods In'],
    'goods-in'              => ['title'=>'Goods In'],

    'probes'                => ['title'=>'Probes'],
    'probes areas'          => ['title'=>'Probe Areas'],
    'probes devices'        => ['title'=>'Probe Devices'],
    'probes menu items'     => ['title'=>'Probe Menu Items'],
    'pods areas'            => ['title'=>'Pod Areas'],
    'pods sensors'          => ['title'=>'Pod Sensors'],
    'pods'                  => ['title'=>'Pod sensors'],
    'staffs'                => ['title'=>'Staffs'],
    'forms'                 => ['title'=>'Forms'],

    'items'                 => ['title'=>'Items'],
    'profile'               => ['title'=>'Profile'],
    'reports'               => ['title'=>'Reports'],
    'navinotes'             => ['title'=>'Navinotes'],
    'check list'            => ['title'=>'Check List'],
    'notifications'         => ['title'=>'Notifications'],
    'cleaning schedule'     => ['title'=>'Cleaning Schedules'],
    'outstanding tasks'     => ['title'=>'Outstanding Tasks'],
    'unit'                  => ['title'=>'Unit'],
    'units'                 => ['title'=>'Units'],
    'food incidents'        => ['title'=>'Food Incidents'],
    'calendar'              => ['title'=>'Calendar'],
    'index'                 => ['title'=>'Dashboard'],
    'areas'                 => ['title'=>'Areas'],
    'staff'                 => ['title'=>'Staff'],
    'haccp'                 => ['title'=>'Haccp'],
    'haccp site'            => ['title'=>'Haccp Site'],
    'haccp company'         => ['title'=>'Haccp Company'],
    'haccp generic'         => ['title'=>'Haccp Generic'],
    'knowledge'             => ['title'=>'Knowledge'],
    'knowledge individual'  => ['title'=>'Knowledge Individual'],
    'knowledge specific'    => ['title'=>'Knowledge Specific'],
    'knowledge site'        => ['title'=>'Knowledge Site'],
    'knowledge company'     => ['title'=>'Knowledge Company'],
    'knowledge generic'     => ['title'=>'Knowledge Generic'],
    'trainings'             => ['title'=>'Training Records'],
    'temperatures alert box'=> ['title'=>'Temperatures Alert Box'],
    'suppliers'             => ['title'=>'Suppliers'],
    'area manager'          => ['title'=>'Area Manager'],
    'area manager stats'    => ['title'=>'Area Manager Stats'],
    'use navitas'          => ['title'=>'Use navitas as'],
    'use_navitas'          => ['title'=>'Use navitas as'],

    'compliance diary'      => ['title'=>'Compliance diary'],
    'auto messages'         => ['title'=>'Auto Messages'],



    'cleaning_schedules_log'     => [
        'title'=>'Cleaning Schedules',
        'messages'=>[
            'outstanding_tasks'=>'Cleaning schedule not completed.'
        ]
    ],
    'cleaning_schedules_items'     => [
        'title'=>'Cleaning Schedules',
        'messages'=>[
            'outstanding_tasks'=>'Cleaning schedule not completed.'
        ]
    ],

    'staff'      => [
        'title'    => 'Staff',
    ],
    'temperatures'      => [
        'title'    => 'Temperatures',
    ],
    'check list'      => [
        'title'    => 'Check List',
    ],
    'monthly-check-list'    => [
        'title'=>'Monthly Check List',
    ],

    'health-questionnaires' => [
        'title'=>'Health Questionnaires',

        'question_1' => 'Whilst you have been absent from work, have you suffered from diarrhoea, vomoting, or other stomach disorder?',
        'question_2' => 'Have you been in Contact with anyone from the above illness?',
        'question_3' => 'Whilst you have been absent from work, have you suffered from any septic or abnormal discharge from ears, eyes, nose, or skin infections?',
        'question_4' => 'Are you aware of any medical problem you may have?',
        'question_5' => 'As you have answered yes to one or more of the above questions, can you confirm you are fit to return to work?',

        'labels'=>[
            'type' => 'Type',
            'type_1' => 'Returning to Work',
            'type_2' => 'New Starter',
            'type_3' => 'Visitor',
            'date' => 'Date of original Assessment',
            'ques_and_ans' => 'Questions and Answers',
            'text_1' => 'Just Returned To Work? Starter? or a Visitor, Please Fill The Form',
            'text_2' => 'This form must be completed by all Food Handlers on return to work following absence due to illness or holidays',
            'text_3' => 'Please Answer the Following Questions',
            'text_4' => 'Guidance to Manager',
            'text_5' => 'If the answer to any of the above Questions is “Yes” the member of the staff should be refereed to their General Practitoner and should not be allowed to work until medical clearance has been given. A copy of the medical clearance note should be attached to this form, or alternatively the member of staff shall sign the folowing statment',
            'text_6' => 'I can confirm that i have been cleared to continue my work as a food handler by',
        ],
    ],


    'check_list_items' => [
        'title' => 'Check List',
        'messages' => [
            'outstanding_tasks' => 'Check list task.'
        ],
        'monthly' => [
            'title' => 'Monthly Check List',
            'messages' => [
                'outstanding_tasks' => 'Outstanding monthly check list task.'
            ],
        ],
        'daily' => [
            'title' => 'Daily Check List',
            'messages' => [
                'outstanding_tasks' => 'Outstanding daily check list task.'
            ],
        ],
    ],
    'temperatures_for_pods'      => [
        'title'    => 'Pod Temperatures',
        'messages' => ['outstanding_tasks' => 'Pod temperature not valid.'],
        'freezers' => ['title'    => 'Freezers Temperatures'],
        'fridges'  => ['title'    => 'Fridges Temperatures']
    ],

    'temperatures_for_probes'      => [
        'title'    => 'Probe Temperatures',
        'messages' => ['outstanding_tasks' => 'Probe temperature not valid.'],
        'probes'   => ['title'    => 'Probes Temperatures'],
    ],

    'temperatures_for_goods_in'      => [
        'title'    => 'Goods In',
        'messages' => ['outstanding_tasks' => 'Goods In non compliant.'],
    ],

    'training_records'      => [
        'title'    => 'Training Records',
        'messages' => ['outstanding_tasks' => 'Training record due to expire.'],
    ],

    'food_incidents'      => [
        'title'    => 'Food Incidents',
        'messages' => ['outstanding_tasks' => 'Food incident appear.'],
    ],
    'health_questionnaires'      => [
        'title'    => 'Health Questionnaires',
        'messages' => ['outstanding_tasks' => 'Health Questionnaire submitted.'],
    ],

    'forms_answers'      => [
        'title'    => 'Forms data',
        'messages' => ['outstanding_tasks' => 'Summary form data are Non compliant.'],
    ],

    'navinotes'      => [
        'title'    => 'Navinotes',
        'messages' => ['outstanding_tasks' => 'Navinote submitted.'],
    ],

    'messages'      => [
        'title'    => 'Messages',
        'emails'   => [
            'new_message'=>'New message',
            'new_message_has_appeared'=>'New message has appeared in the thread, where you are assigned to.',
        ],
    ],
    'knowledges' => [
        'title'=>'Knowledge',
        'columns'=>[
            'content_one' => [
                'generic' => 'What the Law States',
                'individual' => 'Your standard',
                'specific' => 'Your standard',
            ],
            'content_two' => [
                'generic' => '',
                'individual' => 'Your standard',
                'specific' => 'Your standard',
            ],
        ],
    ],
    'forms manager'=>['title'=>'Forms manager'],
    'health questionnaires'=>['title'=>'Health questionnaires'],
    'health_questionnaires'=>['title'=>'Health questionnaires'],
    'cleaning schedule'=>['title'=>'Cleaning schedule'],
    'cleaning_schedule'=>['title'=>'Cleaning schedule'],
    'new cleaning schedule'=>['title'=>'Cleaning schedule'],

    'check list daily'=>['title'=>'Daily check list'],
    'check_list_daily'=>['title'=>'Daily check list'],
    'check list monthly'=>['title'=>'Monthly check list'],
    'check_list_monthly'=>['title'=>'Monthly check list'],


    /* Area Manager titles */
    'users'      => [
        'title'    => 'Users',
    ],    
    'usage_stats'      => [
        'title'    => 'Usage Stats',
    ],
    'usage stats'      => [
        'title'    => 'Usage Stats',
    ],
    'site_stats'      => [
        'title'    => 'Site Stats',
    ], 
    'haccp_company'      => [
        'title'    => 'HACCP Company',
    ], 
    'knowledge_company'      => [
        'title'    => 'Knowledge Company',
    ],
    'knowledge_company'      => [
        'title'    => 'Knowledge Company',
    ],
    'online users'  => [
        'title'    => 'Online Users',
    ],
    'non compliant trends'  => [
        'title'    => 'Non compliant trends',
    ],
    'new compliance diary'  => [
        'title'    => 'Compliance diary',
    ],
    'haccp_sites'  => [
        'title'    => 'Haccp Sites',
    ],
    'knowledge_sites'  => [
        'title'    => 'Knowledge Sites',
    ],

);
