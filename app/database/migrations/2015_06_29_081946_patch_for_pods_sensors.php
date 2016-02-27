<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchForPodsSensors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement('ALTER TABLE temperatures_pods_sensors CHANGE identifier identifier VARCHAR(30) NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE temperatures_for_pods CHANGE pod_ident pod_ident VARCHAR(30) NULL DEFAULT NULL;');

        Schema::create('temperatures_hubs_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('hub_id');
            $table->string('ip');
            $table->timestamps();
        });
        Schema::table('temperatures_for_pods', function($table)
        {
            $table->MediumInteger('battery_level')->default(0);
            $table->renameColumn('voltage', 'battery_voltage');
            $table->integer('hub_log_id')->unsigned()->nullable()->default(NULL);
            $table->foreign('hub_log_id')->references('id')->on('temperatures_hubs_logs')->onDelete('cascade');
        });

        Schema::create('temperatures_pods_sensors_na', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('hub_log_id')->nullable()->unsigned()->default(NULL);
            $table->foreign('hub_log_id')->references('id')->on('temperatures_hubs_logs')->onDelete('cascade');
            $table->string('pod_ident',30)->nullable()->default('NULL');
            $table->MediumInteger('battery_level')->default(0);
            $table->decimal('battery_voltage',3,1 )->default(0);
            $table->decimal('temperature',3,1 )->default(0);
            $table->Integer('timestamp');
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
