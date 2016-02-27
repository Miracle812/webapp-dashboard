<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTemperaturesPodsTimeExclude extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('temperatures_pods_time_exclude', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('area_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('temperatures_pods_areas')->onDelete('cascade');
            $table->string('week_days')->nullable()->default(NULL);
            $table->TinyInteger('all_day')->default(0);
            $table->string('from')->nullable()->default(NULL);
            $table->string('to')->nullable()->default(NULL);
            $table->TinyInteger('active')->default(1);
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
