<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMobilePhone extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function($table)
        {
            $table->String('mobile_phone',20)->nullable()->default(NULL);
        });
        Schema::table('units', function($table)
        {
            $table->String('mobile_phone',20)->nullable()->default(NULL);
        });
        Schema::table('headquarters', function($table)
        {
            $table->String('mobile_phone',20)->nullable()->default(NULL);
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
