<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResolveTemperatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('temperatures_resolved', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments  ('id');
			$table->integer     ('unit_id')->unsigned();
			$table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->text        ('comment')->nullable()->default(NULL);
			$table->string      ('trends')->nullable()->default(NULL);
			$table->TinyInteger ('resolved')->default(0);
			$table->timestamps();
		});

		if(!Schema::hasColumn('temperatures_for_pods', 'resolve_id'))
			Schema::table('temperatures_for_pods', function($table)
			{
				$table->integer('resolved_id')->unsigned()->nullable()->default(NULL);
				$table->foreign('resolved_id')->references('id')->on('temperatures_resolved')->onDelete('set null');
			});

		if(!Schema::hasColumn('temperatures_for_probes', 'resolve_id'))
			Schema::table('temperatures_for_probes', function($table)
			{
				$table->integer('resolved_id')->unsigned()->nullable()->default(NULL);
				$table->foreign('resolved_id')->references('id')->on('temperatures_resolved')->onDelete('set null');
			});

		if(!Schema::hasColumn('temperatures_for_goods_in', 'resolve_id'))
			Schema::table('temperatures_for_goods_in', function($table)
			{
				$table->integer('resolved_id')->unsigned()->nullable()->default(NULL);
				$table->foreign('resolved_id')->references('id')->on('temperatures_resolved')->onDelete('set null');
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
