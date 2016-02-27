<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchDbCleaningSchedules extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('new_cleaning_schedules_submitted', function($table)
		{
			$table->dropColumn('task_id');
			$table->dropColumn('task_item_id');
		});

		Schema::table('new_cleaning_schedules_submitted', function($table)
		{
			$table->integer('task_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('task_id')->references('id')->on('new_cleaning_schedules_tasks2')->onDelete('set NULL');
			$table->integer('task_item_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('task_item_id')->references('id')->on('new_cleaning_schedules_items2')->onDelete('set NULL');
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
