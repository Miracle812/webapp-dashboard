<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescriptionForTemperaturesPodsSensors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('temperatures_pods_sensors', 'description'))
			Schema::table('temperatures_pods_sensors', function($table)
			{
				$table->text('description')->nullable()->default(NULL);
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
