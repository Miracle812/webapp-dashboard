<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoMessagesGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('auto_messages_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name',255);
            $table->string('target_type',255);
            $table->integer('weekends')->default(1); //include weekends
            $table->string('freq_type',20)->nullable()->default('day'); //frequency for messages
            $table->integer('freq_value')->nullable()->default(1);
            $table->string('delay_type',20)->nullable()->default(NULL); //delay for first message
            $table->integer('delay_value')->nullable()->default(NULL);
            $table->string('send_hour',10)->default('10:00');
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
