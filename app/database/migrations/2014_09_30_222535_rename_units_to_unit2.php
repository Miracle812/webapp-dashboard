<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUnitsToUnit2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('staff_details', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });

        Schema::table('suppliers_details', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });

        Schema::table('training_records', function($table)
        {
            $table->renameColumn('units_id', 'unit_id');
        });

        Schema::table('users', function($table)
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
