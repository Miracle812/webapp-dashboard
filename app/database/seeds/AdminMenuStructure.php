<?php

class AdminMenuStructure extends Seeder {

    public function run()
    {
        DB::table('menu_structures')->where('target_type','admin')->delete();
        $countIncrement = (count(\Model\MenuStructures::all())+1);
        DB::statement('ALTER TABLE menu_structures AUTO_INCREMENT = '.$countIncrement.';');
        $root = ['target_type'=>'admin','root'=>0,'lft'=>0,'rgt'=>0,'active'=>1,'lang'=>'en','lvl'=>0,'sort'=>0,'active'=>0,'title'=>'ROOT','menu_title'=>'ROOT','type'=>NULL,'route_path'=>NULL,'link'=>NULL,];
        $root = \Model\MenuStructures::create($root);
        $rootId = $root->id;
        $root -> update(['target_id'=>$rootId]);
        $records = [
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>1,'title'=>'Clients Manager:','menu_title'=>'Clients Manager:','type'=>'first-child','route_path'=>NULL,'ico'=>''],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>2,'title'=>'Clients','menu_title'=>'Clients','type'=>'module','route_path'=>'/headquarters','ico'=>'fa fa-home'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>3,'title'=>'Sites','menu_title'=>'Sites','type'=>'module','route_path'=>'/units','ico'=>'fa fa-home'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Users','menu_title'=>'Users','type'=>'module','route_path'=>'/users','ico'=>'fa fa-users'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>4,'title'=>'Hardware','menu_title'=>'Hardware','type'=>'module','route_path'=>'/hardware','ico'=>'fa fa-wrench'],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>5,'title'=>'Menu Structure:','menu_title'=>'Menu Structure:','type'=>'first-child','route_path'=>NULL,'ico'=>''],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>6,'title'=>'Admins','menu_title'=>'Admins','type'=>'module','route_path'=>'/menu-admin','ico'=>'fa fa-th-list'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>7,'title'=>'Hq Managers','menu_title'=>'Hq Managers','type'=>'module','route_path'=>'/menu-hq','ico'=>'fa fa-th-list'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>8,'title'=>'Area Managers','menu_title'=>'Area Managers','type'=>'module','route_path'=>'/menu-area','ico'=>'fa fa-th-list'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>9,'title'=>'Local Managers','menu_title'=>'Local Managers','type'=>'module','route_path'=>'/menu-local','ico'=>'fa fa-th-list'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>10,'title'=>'Visitors','menu_title'=>'Visitors','type'=>'module','route_path'=>'/menu-visitor','ico'=>'fa fa-th-list'],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>11,'title'=>'Generic Data:','menu_title'=>'Generic Data:','type'=>'first-child','route_path'=>NULL,'ico'=>''],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>12,'title'=>'Haccp Generic','menu_title'=>'Haccp Generic','type'=>'module','route_path'=>'/haccp-generic','ico'=>'fa fa-folder-open'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>13,'title'=>'Knowledge Generic','menu_title'=>'Knowledge Generic','type'=>'module','route_path'=>'/knowledge-generic','ico'=>'fa fa-book'],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>14,'title'=>'Administrator:','menu_title'=>'Administrator:','type'=>'first-child','route_path'=>NULL,'ico'=>''],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>15,'title'=>'Non complaint Trends','menu_title'=>'Non complaint Trends','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-bar-chart-o', 'childrens'=>[
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Dropdown menu', 'menu_title'=>'Dropdown menu', 'type'=>'module','route_path'=>'/non-compliant-trends'],
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Answers', 'menu_title'=>'Answers', 'type'=>'module','route_path'=>'/non-compliant-trends/answers'],
            ]],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>16,'title'=>'Support system','menu_title'=>'Support system','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-life-saver', 'childrens'=>[
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Tickets', 'menu_title'=>'Tickets', 'type'=>'module','route_path'=>'/support-system/index'],
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Assigned Team', 'menu_title'=>'Assigned Team', 'type'=>'module','route_path'=>'/support-system/categories'],
            ]],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>17,'title'=>'Billing','menu_title'=>'Billing','type'=>'first-child','route_path'=>NULL,'ico'=>'fa fa-money', 'childrens'=>[
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>1, 'title'=>'Clients list', 'menu_title'=>'Clients list', 'type'=>'module','route_path'=>'/billing/index'],
                ['target_type'=>'admin','root'=>$rootId,'lvl'=>2,'sort'=>2, 'title'=>'Assigning', 'menu_title'=>'Assigning', 'type'=>'module','route_path'=>'/billing/assigning'],
            ]],

            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>18,'title'=>'Auto Messages','menu_title'=>'Auto Messages','type'=>'module','route_path'=>'/auto-messages','ico'=>'fa fa-envelope'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>19,'title'=>'Options Menu','menu_title'=>'Options Menu','type'=>'module','route_path'=>'/options-menu','ico'=>'fa fa-list-alt'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>20,'title'=>'Forms manager','menu_title'=>'Forms manager','type'=>'module','route_path'=>'/forms-manager','ico'=>'fa fa-file-text'],
            ['target_type'=>'admin','root'=>$rootId,'lvl'=>1,'sort'=>21,'title'=>'Logout','menu_title'=>'Logout','type'=>'link','link'=>'/logout','ico'=>'fa fa-power-off'],
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