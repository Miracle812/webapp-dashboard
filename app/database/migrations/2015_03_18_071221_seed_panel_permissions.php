<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedPanelPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('permissions', function($table)
        {
            $table->string('route',255);
            $table->tinyInteger('disabled')->default(0);
        });

        Schema::table('options_menu', function($table)
        {
            $table->string('type',25);
            $table->tinyInteger('integer')->default(0);
        });

        Schema::table('options', function($table)
        {
            $table->dropColumn('value');
            $table->text('values',25)->nullable();
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
