<?php

class LocalManagerMenuStructure extends Seeder {

    public function run()
    {
        DB::table('menu_structures')->where('target_type','local-manager')->delete();
        $countIncrement = (count(\Model\MenuStructures::all())+1);
        DB::statement('ALTER TABLE menu_structures AUTO_INCREMENT = '.$countIncrement.';');
        $root = ['target_type'=>'local-manager','root'=>0,'lft'=>0,'rgt'=>0,'active'=>1,'lang'=>'en','lvl'=>0,'sort'=>0,'active'=>0,'title'=>'ROOT','menu_title'=>'ROOT','type'=>NULL,'route_path'=>NULL,'link'=>NULL,];
        $root = \Model\MenuStructures::create($root);
        $rootId = $root->id;
        $root -> update(['target_id'=>$rootId]);
        $records = [
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>1,'title'=>'Settings','menu_title'=>'Settings','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-cogs',
                'childrens'=>[
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Site Haccp',           'menu_title'=>'Site Haccp',           'type'=>'module','route_path'=>'/site-haccp'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Site Knowledge',       'menu_title'=>'Site Knowledge',       'type'=>'module','route_path'=>'/site-knowledge'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>3, 'title'=>'Probe Settings',       'menu_title'=>'Probe Settings',       'type'=>'first-child','route_path'=>NULL,
                        'childrens'=>[
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>1, 'title'=>'Probe Areas','menu_title'=>'Probe Areas','type'=>'module','route_path'=>'/probes/areas'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>2, 'title'=>'Probe Devices','menu_title'=>'Probe Devices','type'=>'module','route_path'=>'/probes/devices'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>3, 'title'=>'Probe Menu Items','menu_title'=>'Probe Menu Items','type'=>'module','route_path'=>'/probes/menu-items'],
                        ]
                    ],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>4,'title'=>'Pod Settings','menu_title'=>'Pod Settings','type'=>'first-child','route_path'=>NULL,
                        'childrens'=>[
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>1,'title'=>'Pod Areas','menu_title'=>'Pod Areas','type'=>'module','route_path'=>'/pods/areas'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>2,'title'=>'Pod Devices','menu_title'=>'Pod Devices','type'=>'module','route_path'=>'/pods/sensors'],
                        ]
                    ],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>5,'title'=>'Widgets','menu_title'=>'Widgets','type'=>'first-child','route_path'=>NULL,
                        'childrens'=>[
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>1,'title'=>'Temperatures Box','menu_title'=>'Temperatures Box','type'=>'module','route_path'=>'/temperatures-alert-box'],
                        ]
                    ],
                ]
            ],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>2,'title'=>'Records','menu_title'=>'Records','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-folder-open',
                'childrens'=>[
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Staff',                'menu_title'=>'Staff',                'type'=>'module','route_path'=>'/staff','ico'=>'fa fa-folder-open'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Health Questionnaires','menu_title'=>'Health Questionnaires','type'=>'module','route_path'=>'/health-questionnaires'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>3, 'title'=>'Training Record',      'menu_title'=>'Training Record',      'type'=>'module','route_path'=>'/trainings'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>4, 'title'=>'Suppliers',            'menu_title'=>'Suppliers',            'type'=>'module','route_path'=>'/suppliers'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>5, 'title'=>'Goods In Record',      'menu_title'=>'Goods In Record',      'type'=>'module','route_path'=>'/goods-in'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>6, 'title'=>'Food Incident Record', 'menu_title'=>'Food Incident Record', 'type'=>'module','route_path'=>'/food-incidents'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>7, 'title'=>'Cleaning Schedule',    'menu_title'=>'Cleaning Schedule',    'type'=>'module','route_path'=>'/new-cleaning-schedule'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>8, 'title'=>'Compliance Diary',     'menu_title'=>'Compliance Diary',     'type'=>'module','route_path'=>'/new-compliance-diary'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>9, 'title'=>'Check List',           'menu_title'=>'Check List',          'type'=>'module','route_path'=>'/check-list'],
                ]
            ],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>3,'title'=>'Temperatures','menu_title'=>'Temperatures','type'=>'module','route_path'=>'/temperatures','ico'=>'fa fa-tachometer',
                'childrens' => [
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>1,'title'=>'Probe Temperatures','menu_title'=>'Probe Temperatures','type'=>'module','route_path'=>'/temperatures/probes',
                        'childrens'=>[
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>1,'title'=>'Chilling',    'menu_title'=>'Chilling',    'type'=>'module','route_path'=>'/temperatures/probes/1'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>2,'title'=>'Re heating',  'menu_title'=>'Re heating',  'type'=>'module','route_path'=>'/temperatures/probes/5'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>3,'title'=>'Cooking',     'menu_title'=>'Cooking',     'type'=>'module','route_path'=>'/temperatures/probes/2'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>4,'title'=>'Hot Service', 'menu_title'=>'Hot Service', 'type'=>'module','route_path'=>'/temperatures/probes/4'],
                            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>3,'sort'=>5,'title'=>'Cold Service','menu_title'=>'Cold Service','type'=>'module','route_path'=>'/temperatures/probes/3']
                        ],
                    ],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>2,'title'=>'Pod Temperatures','menu_title'=>'Pod Temperatures','type'=>'module','route_path'=>'/temperatures/pods'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>3,'title'=>'Temperatures Forms','menu_title'=>'Temperatures Forms','type'=>'module','route_path'=>'/temperatures/forms'],
                ],
            ],

            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Knowledge','menu_title'=>'Knowledge','type'=>'module','route_path'=>'/knowledge','ico'=>'fa fa-book',
                'childrens'=>[
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Knowledge',   'menu_title'=>'Knowledge',    'type'=>'module','route_path'=>'/knowledge/storage',],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Knowledge forms',     'menu_title'=>'Knowledge forms',      'type'=>'module','route_path'=>'/knowledge/forms'],
                ]
            ],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>5,'title'=>'HACCP',    'menu_title'=>'HACCP',    'type'=>'module','route_path'=>'/haccp',    'ico'=>'fa fa-exclamation-triangle',
                'childrens'=>[
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'HACCP',    'menu_title'=>'HACCP',    'type'=>'module','route_path'=>'/haccp/storage'],
                    ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'HACCP forms',      'menu_title'=>'HACCP forms',      'type'=>'module','route_path'=>'/haccp/forms'],
                ]
            ],

            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>6,'title'=>'Reports',  'menu_title'=>'Reports',  'type'=>'module','route_path'=>'/reports',  'ico'=>'fa fa-file'],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>7,'title'=>'Navinotes','menu_title'=>'Navinotes','type'=>'module','route_path'=>'/navinotes','ico'=>'fa fa-chain'],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>8,'title'=>'Support',  'menu_title'=>'Support',  'type'=>'module','route_path'=>'/support-system',  'ico'=>'fa fa-life-saver'],
            ['target_type'=>'local-manager','root'=>$rootId,'lvl'=>1,'sort'=>9,'title'=>'Logout',   'menu_title'=>'Logout',   'type'=>'link',  'route_path'=>NULL,        'ico'=>'fa fa-power-off','link'=>'logout'],
        ];
        $this -> insertRecords($records, $rootId);
    }

    public function insertRecords($records, $parentId)
    {
        foreach($records as $record){
            $childrens = [];
            if(isset($record['childrens'])){
                $childrens = $record['childrens'];
                unset($record['childrens']);
            }
            $data = $record+['target_id'=>$parentId,'lft'=>0,'rgt'=>0,'active'=>1,'lang'=>'en'];
            $parent = \Model\MenuStructures::create($data);
            if($childrens){
                $this->insertRecords($childrens, $parent->id);
            }
        }
    }
}

