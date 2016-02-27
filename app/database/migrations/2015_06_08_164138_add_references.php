<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferences extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('cleaning_schedules', function ($table)
        {
            $unitsIds = \Model\Units::lists('id');
            \Model\CleaningSchedules::whereNotIn('unit_id', $unitsIds)->delete();
            DB::statement("ALTER TABLE cleaning_schedules CHANGE COLUMN unit_id unit_id INT(11) UNSIGNED NOT NULL ");
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
/*
        Schema::table('notifications', function ($table)
        {
            $table->dropForeign('notifications_ibfk_2');
        });

        Schema::table('notifications', function ($table)
        {
            DB::statement("ALTER TABLE notifications CHANGE COLUMN unit_id unit_id INT(11) UNSIGNED NOT NULL ");
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
*/
        Schema::table('check_list_actions', function ($table)
        {
            $table->dropForeign('check_list_actions_ibfk_1');
            $table->dropForeign('check_list_actions_ibfk_2');
            $table->dropForeign('check_list_actions_ibfk_3');
        });



        Schema::table('check_list_actions', function ($table)
        {
            DB::statement("ALTER TABLE check_list_actions CHANGE COLUMN unit_id unit_id INT(11) UNSIGNED NULL ");
            DB::statement("ALTER TABLE check_list_actions CHANGE COLUMN user_id user_id INT(11) UNSIGNED NULL ");
            DB::statement("ALTER TABLE check_list_actions CHANGE COLUMN task_id task_id INT(11) UNSIGNED NULL ");

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('check_list_tasks')->onDelete('cascade');
        });

        Schema::table('compliance_diary', function ($table)
        {
            $unitsIds = \Model\Units::lists('id');
            \Model\ComplianceDiary::whereNotIn('unit_id', $unitsIds)->delete();
            DB::statement("ALTER TABLE compliance_diary CHANGE COLUMN unit_id unit_id INT(11) UNSIGNED NULL ");
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });

        Schema::table('files', function ($table)
        {
            $unitsIds = \Model\Units::lists('id');
            \Model\Files::whereNotIn('unit_id', $unitsIds)->delete();
            DB::statement("ALTER TABLE files CHANGE COLUMN unit_id unit_id INT(11) UNSIGNED NULL ");
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });


        Schema::rename('check_list_actions', 'old_check_list_actions');
        Schema::rename('check_list_sections', 'old_check_list_sections');
        Schema::rename('check_list_tasks', 'old_check_list_tasks');
        Schema::rename('events', 'old_events');
        Schema::rename('notifications', 'old_notifications');
        Schema::rename('units_contacts', 'old_units_contacts');
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
