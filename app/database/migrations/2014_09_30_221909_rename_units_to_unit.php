<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUnitsToUnit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('check_list_actions', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('check_list_tasks', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('freezers_devices', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('fridges_devices', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('haccp', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('health_questionnaires', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('notes', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('outstanding_task', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('photos', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('probes_devices', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });
        Schema::table('signatures', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
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
