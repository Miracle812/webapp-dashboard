<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOptionsMenu extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('options_menu', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('identifier',50);
            $table->string('name',50);
            $table->timestamps();
        });
        DB::table('options_menu')->insert(
            [
                ['id'=>1,'parent_id' => 0, 'identifier' => 'units' , 'name'=>'Units'],
                ['id'=>2,'parent_id' => 0, 'identifier' => 'headquarters' , 'name'=>'Headquarters'],
                ['id'=>3,'parent_id' => 0, 'identifier' => 'users' , 'name'=>'Users'],
                ['id'=>4,'parent_id' => 1, 'identifier' => 'units_haccp_generic_disable' , 'name'=>'Disable Haccp Generic'],
                ['id'=>5,'parent_id' => 1, 'identifier' => 'units_knowledge_generic_disable' , 'name'=>'Disable Knowledge Generic']
            ]
        );
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
