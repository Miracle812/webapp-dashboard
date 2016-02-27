<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NonCompliantTrends extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('noncompliant_trends', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->Integer('sort')->default(0);
            $table->String('name',100);
            $table->timestamps();
        });
        Schema::table('outstanding_task', function($table)
        {
            $table->string ('trends')->nullable()->default(NULL);
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
