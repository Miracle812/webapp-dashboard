<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJobNumberToGoodsInTemperatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasColumn('temperatures_for_goods_in', 'job_number'))
			Schema::table('temperatures_for_goods_in', function($table)
			{
				$table->String('job_number')->nullable()->default(NULL);
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