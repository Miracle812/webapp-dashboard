<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();
        DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1;');

        DB::table('permission_role')->delete();
        DB::statement('ALTER TABLE permission_role AUTO_INCREMENT = 1;');

        $moduleOptionsIds = DB::table('options_menu')->whereIdentifier('manage_unit_modules')->lists('id');
        DB::table('options')->whereIn('option_id',$moduleOptionsIds)->delete();
        $countIncrement = (count(\Model\Options::all())+1);
        DB::statement('ALTER TABLE options AUTO_INCREMENT = '.$countIncrement.';');

    $permissions = [
        ['route'=>'searching','name'=>'panel-manage-searching', 'display_name'=>'module searching'],
        ['route'=>'messages','name'=>'panel-manage-messages', 'display_name'=>'module messages'],
        ['route'=>'pods','name'=>'panel-manage-pods', 'display_name'=>'module pods'],
        ['route'=>'form-builder','name'=>'panel-manage-form-builder', 'display_name'=>'module form builder'],
        ['route'=>'suppliers','name'=>'panel-manage-suppliers', 'display_name'=>'module suppliers'],
        ['route'=>'navinotes','name'=>'panel-manage-navinotes', 'display_name'=>'module navinotes'],
        ['route'=>'temperatures','name'=>'panel-manage-temperatures', 'display_name'=>'module temperatures'],
        ['route'=>'signatures','name'=>'panel-manage-signatures', 'display_name'=>'module signatures'],
        ['route'=>'goods-in','name'=>'panel-manage-goods-in', 'display_name'=>'module goods in'],
        ['route'=>'staff','name'=>'panel-manage-staff', 'display_name'=>'module staff'],
        ['route'=>'trainings','name'=>'panel-manage-trainings', 'display_name'=>'module trainings'],
        ['route'=>'health-questionnaires','name'=>'panel-manage-health-questionnaires', 'display_name'=>'module health questionnaires'],
        ['route'=>'reports','name'=>'panel-manage-reports', 'display_name'=>'module reports'],
        ['route'=>'temperatures-alert-box','name'=>'panel-manage-temperatures-alert-box', 'display_name'=>'module temperatures alert box'],
        ['route'=>'food-incidents','name'=>'panel-manage-food-incidents', 'display_name'=>'module food incidents'],
        ['route'=>'notifications','name'=>'panel-manage-notifications', 'display_name'=>'module notifications'],
        ['route'=>'areas','name'=>'panel-manage-areas', 'display_name'=>'module areas'],
        ['route'=>'compliance-diary','name'=>'panel-manage-compliance-diary', 'display_name'=>'module compliance diary'],
        ['route'=>'cleaning-schedule','name'=>'panel-manage-cleaning-schedule', 'display_name'=>'module cleaning schedule'],
        ['route'=>'outstanding-tasks','name'=>'panel-manage-outstanding-tasks', 'display_name'=>'module outstanding tasks'],
        ['route'=>'check-list','name'=>'panel-manage-check-list', 'display_name'=>'module check list'],
        ['route'=>'site-haccp','name'=>'panel-manage-haccp-site', 'display_name'=>'module haccp site'],
        ['route'=>'haccp','name'=>'panel-manage-haccp', 'display_name'=>'module haccp'],
        ['route'=>'knowledge','name'=>'panel-manage-knowledge', 'display_name'=>'module knowledge'],
        ['route'=>'site-knowledge','name'=>'panel-manage-knowledge-site', 'display_name'=>'module knowledge site'],
        ['route'=>'photos','name'=>'panel-manage-photos', 'display_name'=>'module photos'],
        ['route'=>'items','name'=>'panel-manage-items', 'display_name'=>'module items'],
        ['route'=>'profile','name'=>'panel-manage-profile', 'display_name'=>'module profile'],
        ['route'=>'unit','name'=>'panel-manage-unit', 'display_name'=>'module unit'],
        ['route'=>'probes','name'=>'panel-manage-probes', 'display_name'=>'module probes']
    ];

        $role_id = Role::whereName('local-manager')->first()->id;
        foreach($permissions as $permission){
            $permId = DB::table('permissions')->insertGetId( $permission );
            DB::table('permission_role')->insert( ['role_id' => $role_id,'permission_id' => $permId] );
        }
    }
}