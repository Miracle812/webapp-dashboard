<?php

class VisitorsMenuStructure extends Seeder {

    public function run()
    {
        DB::table('menu_structures')->where('target_type','visitor')->delete();
        $countIncrement = (count(\Model\MenuStructures::all())+1);
        DB::statement('ALTER TABLE menu_structures AUTO_INCREMENT = '.$countIncrement.';');
        $root = ['target_type'=>'visitor','root'=>0,'lft'=>0,'rgt'=>0,'active'=>1,'lang'=>'en','lvl'=>0,'sort'=>0,'active'=>0,'title'=>'ROOT','menu_title'=>'ROOT','type'=>NULL,'route_path'=>NULL,'link'=>NULL,];
        $root = \Model\MenuStructures::create($root);
        $rootId = $root->id;
        $root -> update(['target_id'=>$rootId]);
        $records = [
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>1,'title'=>'Records','menu_title'=>'Records','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-folder-open',
                'childrens'=>[
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Staff',                'menu_title'=>'Staff',                'type'=>'module','route_path'=>'/staff','ico'=>'fa fa-folder-open'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Suppliers',            'menu_title'=>'Suppliers',            'type'=>'module','route_path'=>'/suppliers'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>3, 'title'=>'Health Questionnaires','menu_title'=>'Health Questionnaires','type'=>'module','route_path'=>'/health-questionnaires'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>4, 'title'=>'Training Record',      'menu_title'=>'Training Record',      'type'=>'module','route_path'=>'/trainings'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>5, 'title'=>'Goods In Record',      'menu_title'=>'Goods In Record',      'type'=>'module','route_path'=>'/goods-in'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>6, 'title'=>'Food Incident Record', 'menu_title'=>'Food Incident Record', 'type'=>'module','route_path'=>'/food-incidents'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>7, 'title'=>'Cleaning Schedule',    'menu_title'=>'Cleaning Schedule',    'type'=>'module','route_path'=>'/new-cleaning-schedule'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>8, 'title'=>'Compliance Diary',     'menu_title'=>'Compliance Diary',     'type'=>'module','route_path'=>'/compliance-diary'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>9, 'title'=>'Daily Check List',     'menu_title'=>'Daily Check List',     'type'=>'module','route_path'=>'/check-list-daily'],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>10,'title'=>'Monthly Check List',   'menu_title'=>'Monthly Check List',   'type'=>'module','route_path'=>'/check-list-monthly'],
                ]
            ],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>2,'title'=>'Temperatures','menu_title'=>'Temperatures','type'=>'module','route_path'=>'/temperatures','ico'=>'fa fa-tachometer',
                'childrens' => [
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>1,'title'=>'Probe Temperatures','menu_title'=>'Probe Temperatures','type'=>'module','route_path'=>'/temperatures/probes',
                        'childrens'=>[
                            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>3,'sort'=>1,'title'=>'Chilling',    'menu_title'=>'Chilling',    'type'=>'module','route_path'=>'/temperatures/probes/1'],
                            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>3,'sort'=>2,'title'=>'Re heating',  'menu_title'=>'Re heating',  'type'=>'module','route_path'=>'/temperatures/probes/5'],
                            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>3,'sort'=>3,'title'=>'Cooking',     'menu_title'=>'Cooking',     'type'=>'module','route_path'=>'/temperatures/probes/2'],
                            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>3,'sort'=>4,'title'=>'Hot Service', 'menu_title'=>'Hot Service', 'type'=>'module','route_path'=>'/temperatures/probes/4'],
                            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>3,'sort'=>5,'title'=>'Cold Service','menu_title'=>'Cold Service','type'=>'module','route_path'=>'/temperatures/probes/3']
                        ],
                    ],
                    ['target_type'=>'visitor','root'=>$rootId,'lvl'=>2,'sort'=>2,'title'=>'Pod Temperatures','menu_title'=>'Pod Temperatures','type'=>'module','route_path'=>'/temperatures/pods'],
                ],
            ],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>3,'title'=>'Support',  'menu_title'=>'Support',  'type'=>'module','route_path'=>'/support-system',  'ico'=>'fa fa-life-saver'],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Reports',  'menu_title'=>'Reports',  'type'=>'module','route_path'=>'/reports',  'ico'=>'fa fa-file'],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>5,'title'=>'HACCP',    'menu_title'=>'HACCP',    'type'=>'module','route_path'=>'/haccp',    'ico'=>'fa fa-exclamation-triangle'],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>6,'title'=>'Knowledge','menu_title'=>'Knowledge','type'=>'module','route_path'=>'/knowledge','ico'=>'fa fa-book'],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>7,'title'=>'Navinotes','menu_title'=>'Navinotes','type'=>'module','route_path'=>'/navinotes','ico'=>'fa fa-chain'],
            ['target_type'=>'visitor','root'=>$rootId,'lvl'=>1,'sort'=>8,'title'=>'Logout',   'menu_title'=>'Logout',   'type'=>'link',  'route_path'=>NULL,        'ico'=>'fa fa-power-off','link'=>'logout'],
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

