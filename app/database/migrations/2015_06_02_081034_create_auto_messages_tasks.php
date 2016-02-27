<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoMessagesTasks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('auto_messages_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('message_id'); //include weekends
            $table->integer('target_id');
            $table->string('target_type',50); //delay for first message
            $table->dateTime('trigger_date');
        });
        Schema::create('auto_messages_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('message_id'); //include weekends
            $table->integer('target_id');
            $table->string('target_type',50); //delay for first message
            $table->dateTime('trigger_date');
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
