<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserTrackLogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_track_logs', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
            $table->integer('stats_id')->unsigned();
            $table->foreign('stats_id')->references('id')->on('users_statistics')->onDelete('cascade');
			$table->string('url');
			$table->string('method');
			$table->tinyInteger('ajax')->default(0);
			$table->longText('data')->nullable()->default(NULL);
			$table->timestamps();
		});

        Schema::table('users_statistics', function($table)
        {
            $table->String('role',30)->nullable()->default(NULL);
        });
        DB::statement("SET foreign_key_checks=0");
        DB::table('users_statistics')->truncate();
        DB::table('users_track_logs')->truncate();
        DB::statement("SET foreign_key_checks=1");

    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_usage');
	}

}
