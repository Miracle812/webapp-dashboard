<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('options', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('option_id');
            $table->integer('target_id');
            $table->string('target_type',50);
            $table->integer('value')->default(0);
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
