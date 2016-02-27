<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsSmsEmailToAutoMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('auto_messages_groups', function($table)
        {
            $table->tinyInteger('on_sms')->default(0);
            $table->tinyInteger('on_email')->default(1);
        });

        Schema::table('auto_messages_logs', function($table)
        {
            $table->tinyInteger('on_sms')->default(0);
            $table->tinyInteger('on_email')->default(1);
        });

        Schema::table('auto_messages_tasks', function($table)
        {
            $table->tinyInteger('on_sms')->default(0);
            $table->tinyInteger('on_email')->default(1);
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
