<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCleaningSchedule extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('cleaning_schedules_log', function($table)
        {
            $table->String('title',50)->nullable()->default(NULL);
            $table->String('staff_name',200)->nullable()->default(NULL);
            $table->String('form_name',200)->nullable()->default(NULL);
            $table->String('description',200)->nullable()->default(NULL);
        });

        Schema::table('cleaning_schedules_log', function ($table)
        {
            $table->dropForeign('cleaning_schedules_log_task_id_foreign');
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
