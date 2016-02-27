<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnColorToCleanings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('new_cleaning_schedules_tasks2', 'task_color'))
			Schema::table('new_cleaning_schedules_tasks2', function($table)
			{
				$table->string('task_color')->nullable()->default(NULL);
			});
		if(!Schema::hasColumn('new_cleaning_schedules_submitted', 'task_color'))
			Schema::table('new_cleaning_schedules_submitted', function($table)
			{
				$table->string('task_color')->nullable()->default(NULL);
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
