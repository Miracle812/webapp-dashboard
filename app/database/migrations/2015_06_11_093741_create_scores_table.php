<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('scores', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('out_task_id')->unsigned()->nullable()->default(NULL);
            $table->foreign('out_task_id')->references('id')->on('outstanding_task')->onDelete('cascade');
            $table->string('name')->nullable()->default(NULL);
            $table->integer('scores_value');
            $table->string('scores_type');
            $table->integer('scores_summary');
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
