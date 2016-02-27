<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchWorkflowTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('workflow_items', function($table)
		{
			$table->dropColumn('expiry');
			$table->integer('user_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set NULL');
			$table->string('status', 30)->nullable()->default(NULL);
		});


		Schema::table('workflow_logs', function ($table)
		{
			$table->dropForeign('workflow_logs_assigned_id_foreign');
			$table->dropColumn('assigned_id');
			$table->integer('user_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set NULL');
			$table->integer('site_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('site_id')->references('id')->on('units')->onDelete('cascade');
		});

		Schema::table('workflow_tasks', function($table)
		{
			$table->string('tz', 30)->nullable()->default(NULL);
		});

		Schema::table('workflow_completed', function($table)
		{
			$table->string('tz', 30)->nullable()->default(NULL);
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
