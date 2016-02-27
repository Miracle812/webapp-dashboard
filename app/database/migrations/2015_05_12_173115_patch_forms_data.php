<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchFormsData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('forms', function ($table)
        {
            $table->dropColumn('signature');
        });

        Schema::table('forms_answers', function ($table)
        {
            $table->dropColumn('signature');
            $table->dropColumn('sign_name');
            $table->dropColumn('sign_role');
        });

        Schema::table('staffs', function ($table)
        {
            $table->dropColumn('post_code');
            $table->dropColumn('city');
            $table->dropColumn('street_number');
            $table->dropColumn('nin');
        });

        Schema::table('cleaning_schedules', function ($table)
        {
            $table->Integer('form_id')->nullable()->detault(NULL);
            $table->timestamp('from')->default('0000-00-00 00:00:00');
            $table->timestamp('to')->default('0000-00-00 00:00:00');
        });

        Schema::table('cleaning_schedules_log', function ($table)
        {
            $table->Integer('form_answer_id')->nullable()->detault(NULL);
        });
        DB::statement('ALTER TABLE cleaning_schedules_log CHANGE summary summary VARCHAR(255) NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE cleaning_schedules_log CHANGE completed completed TINYINT(4) NULL DEFAULT 0;');

        DB::statement('ALTER TABLE forms_items CHANGE label label TEXT NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE forms_items_logs CHANGE label label TEXT NULL DEFAULT NULL;');
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
