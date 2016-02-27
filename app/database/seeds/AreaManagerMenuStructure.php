<?php

class AreaManagerMenuStructure extends Seeder {

    public function run()
    {
        DB::table('menu_structures')->where('target_type','area-manager')->delete();
        $countIncrement = (count(\Model\MenuStructures::all())+1);
        DB::statement('ALTER TABLE menu_structures AUTO_INCREMENT = '.$countIncrement.';');
        $root = ['target_type'=>'area-manager','root'=>0,'lft'=>0,'rgt'=>0,'active'=>1,'lang'=>'en','lvl'=>0,'sort'=>0,'active'=>0,'title'=>'ROOT','menu_title'=>'ROOT','type'=>NULL,'route_path'=>NULL,'link'=>NULL,];
        $root = \Model\MenuStructures::create($root);
        $rootId = $root->id;
        $root -> update(['target_id'=>$rootId]);
        $records = [
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>1,'title'=>'Units','menu_title'=>'Units','type'=>'module','route_path'=>'/units','ico'=>'fa fa-home'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>2,'title'=>'Users','menu_title'=>'Users','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-users', 'childrens'=>[
                ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Site managers', 'menu_title'=>'Site managers', 'type'=>'module','route_path'=>'/users'],
                ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Visitors', 'menu_title'=>'Visitors', 'type'=>'module','route_path'=>'/users?role=visitor'],
            ]],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>3,'title'=>'Site Stats','menu_title'=>'Site Stats','type'=>'module','route_path'=>'/sitestats','ico'=>'fa fa-bar-chart-o'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Company Haccp','menu_title'=>'Company Haccp','type'=>'module','route_path'=>'/haccp-company','ico'=>'fa fa-folder-open'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Sites Haccp','menu_title'=>'Sites Haccp','type'=>'module','route_path'=>'/haccp-sites','ico'=>'fa fa-folder-open'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>5,'title'=>'Company Knowledge','menu_title'=>'Company Knowledge','type'=>'module','route_path'=>'/knowledge-company','ico'=>'fa fa-book'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>5,'title'=>'Sites Knowledge','menu_title'=>'Sites Knowledge','type'=>'module','route_path'=>'/knowledge-sites','ico'=>'fa fa-book'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>6,'title'=>'Usage Stats','menu_title'=>'Usage Stats','type'=>'module','route_path'=>'/usagestats','ico'=>'fa fa-bar-chart-o'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>7,'title'=>'Use Navitas As','menu_title'=>'Use Navitas As','type'=>'module','route_path'=>'/usenavitas','ico'=>'fa fa-exchange'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>8,'title'=>'Units Map','menu_title'=>'Units Map','type'=>'module','route_path'=>'/units-map','ico'=>'fa fa-map-marker'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>9,'title'=>'Support',  'menu_title'=>'Support',  'type'=>'module','route_path'=>'/support-system',  'ico'=>'fa fa-life-saver'],
            ['target_type'=>'area-manager','root'=>$rootId,'lvl'=>1,'sort'=>10,'title'=>'Logout','menu_title'=>'Logout','type'=>'link','link'=>'/logout','ico'=>'fa fa-power-off'],
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

