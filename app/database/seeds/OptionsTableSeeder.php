<?php

class OptionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('options')->delete();
        DB::table('options_menu')->delete();


        $parents = [
            ['parent_id'=>'0','identifier'=>'units','name'=>'Units', 'type'=>'root'],
            ['parent_id'=>'0','identifier'=>'headquarters','name'=>'Headquarters', 'type'=>'root'],
            ['parent_id'=>'0','identifier'=>'users','name'=>'Users', 'type'=>'root'],
        ];
        $childrens = [
            'units' => [
                ['identifier'=>'hide-haccp-general','name'=>'Hide HACCP General', 'type'=>'checkbox'],
                ['identifier'=>'hide-knowledge-generic','name'=>'Hide Knowledge Generic', 'type'=>'checkbox'],
                ['identifier'=>'manage_unit_modules','name'=>'Manage modules', 'type'=>'feature'],
             ],
            'headquarters' => [
                ['identifier'=>'manage_unit_modules','name'=>'Manage modules', 'type'=>'feature'],
            ],
            'users' => [

            ],
        ];

        foreach($parents as $parent){

            $parentId = DB::table('options_menu')->insertGetId( $parent );
            $childs = $childrens[$parent['identifier']];
            if(count($childs) > 0) {
                foreach ($childs as $child) {
                    $child['parent_id'] = $parentId;
                    DB::table('options_menu')->insert($child);
                }
            }
        }
    }
}
