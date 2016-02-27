<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTablesAndPatch extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('notifications', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('target_id')->nullable()->default(NULL);
            $table->string ('target_type', 50)->nullable()->default(NULL);
            $table->string('receivers_id')->nullable()->default(NULL);
            $table->text ('message')->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('notifications_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('notification_id')->unsigned();
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
            $table->integer('read')->default(0);
            $table->integer('removed')->default(0);
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
