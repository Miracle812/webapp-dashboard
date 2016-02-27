<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRepeatColumnsToComplianceDiary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('compliance_diary', function($table)
        {
            $table->integer('target_id')->nullable();
            $table->string('target_type',25)->nullable();
            $table->integer('repeat_freq')->nullable();
            $table->string('repeat',11)->nullable();
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
