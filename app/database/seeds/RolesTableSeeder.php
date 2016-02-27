<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
        $rolesArr = ['admin','hq-manager','local-manager','visitor','area-manager','accountant','client-relation-officer','new-local-manager'];
        for($i=1;$i<=count($rolesArr);$i++)
        {
            $findRole = \Role::find($i);
            if(!$findRole){
                $new = new \Role();
                $new->id   = $i;
                $new->name = $rolesArr[($i-1)];
                $new->save();
            } elseif($findRole->name !== $rolesArr[($i-1)]) {
                $findRole->name = $rolesArr[($i-1)];
                $findRole->update();
            } else {continue;}
        }
    }
}