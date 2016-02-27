<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('auto_messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->Integer('group_id');
            $table->Integer('sort')->default(0);
            $table->string('title',255);
            $table->text('message');
            $table->tinyInteger('active')->default(0);
            $table->timestamps();
        });
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
