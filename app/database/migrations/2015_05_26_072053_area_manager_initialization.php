<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AreaManagerInitialization extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $role = new \Role ();
        $role -> name = 'area-manager';
        $role -> save();
        $structures = [
            [0, 1, 1, 10, 1, 'Units', 'Units', 'module', '/units', '', 'fa fa-home'],
            [0, 1, 1, 20, 1, 'Users', 'Users', 'module', '/users', '', 'fa fa-users'],
            [0, 1, 1, 30, 1, 'Company Haccp', 'Company Haccp', 'module', '/haccp-company', '', 'fa fa-folder-open'],
            [0, 1, 1, 50, 1, 'Units Map', 'Units Map', 'module', '/units-map', '', 'fa fa-map-marker'],
            [0, 1, 1, 60, 1, 'Logout', 'Logout', 'link', '', 'logout', 'fa fa-power-off'],
            [0, 1, 1, 40, 1, 'Company Knowledge', 'Company Knowledge', 'module', '/knowledge-company', '', 'fa fa-book'],
            [0, 1, 1, 25, 1, 'Site Stats', 'Site Stats', 'module', '/sitestats', NULL, 'fa fa-bar-chart'],
            [0, 1, 1, 43, 1, 'Usage Stats', 'Usage Stats', 'module', '/usagestats', NULL, 'fa fa-bar-chart'],
            [0, 1, 1, 46, 1, 'Use Navitas As', 'Use Navitas As', 'module', '/usenavitas', NULL, 'fa fa-exchange'],
            [0, 1, 1, 5,  1, 'Dashboard', 'Dashboard', 'module', '/index', NULL, 'fa fa-dashboard']
        ];
        list($target_type,$root,$lft,$rgt,$lvl,$sort,$active,$title,$menu_title,$type,$route_path,$link,$ico,$lang) = ['area-manager',0,0,0,0,0,0,'ROOT','ROOT',NULL,NULL,NULL,'','en'];
        $root = \Model\MenuStructures::create(['target_type'=>$target_type,'root'=>$root,'lft'=>$lft,'rgt'=>$rgt,'lvl'=>$lvl,'sort'=>$sort,'active'=>$active,'title'=>$title,'menu_title'=>$menu_title,'type'=>$type,'route_path'=>$route_path,'link'=>$link,'ico'=>$ico,'lang'=>$lang]);
        $root->update(['root'=>$root->id]);
        foreach ($structures as $structure){
            list($lft,$rgt,$lvl,$sort,$active,$title,$menu_title,$type,$route_path,$link,$ico) = $structure;
            \Model\MenuStructures::create(['target_type'=>$target_type,'target_id'=>$root->id,'root'=>$root->id,'lft'=>$lft,'rgt'=>$rgt,'lvl'=>$lvl,'sort'=>$sort,'active'=>$active,'title'=>$title,'menu_title'=>$menu_title,'type'=>$type,'route_path'=>$route_path,'link'=>$link,'ico'=>$ico,'lang'=>$lang]);
        }
    }
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}