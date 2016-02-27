<?php

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        // Add calls to Seeders here
        //$this->call('UsersTableSeeder');
        //$this->call('PostsTableSeeder');
        //$this->call('CommentsTableSeeder');

        $this->call('RolesTableSeeder');
        $this->call('OptionsTableSeeder');
        $this->call('PermissionsTableSeeder');
        $this->call('VisitorsMenuStructure');
        $this->call('AreaManagerMenuStructure');
        $this->call('HqManagerMenuStructure');
        $this->call('LocalManagerMenuStructure');
        $this->call('AdminMenuStructure');
        $this->call('SupportCategories');
    }
}