<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTemperaturesCalibrate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('temperatures_for_calibration', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('unit_id');
            $table->string('device_identifier');
            $table->string('device_name');
            $table->string('temperature');
            $table->timestamps();
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
