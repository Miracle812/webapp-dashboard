<?php

class SupportCategories extends Seeder {

    public function run()
    {
        DB::table('support_categories')->delete();
        DB::statement('ALTER TABLE support_categories AUTO_INCREMENT = 1;');
        DB::table('support_categories')->insert( [
            ['name' => 'Feedback'],
            ['name' => 'General Inquiry'],
            ['name' => 'Report a Problem / Access Issue'],
        ]);
    }
}
