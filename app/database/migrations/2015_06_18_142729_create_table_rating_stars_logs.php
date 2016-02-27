<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRatingStarsLogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('rating_stars_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->TinyInteger('stars');
            $table->text('description');
            $table->timestamps();
        });


        //patch
        $cleaningSchedules = \Model\CleaningSchedules::where(function($query){
            $query->
                where('from','0000-00-00 00:00:00')->
                orWhere('to','0000-00-00 00:00:00');
        })->get();



        foreach($cleaningSchedules as $task)
        {
            if($task -> from == '0000-00-00 00:00:00'){
                $task -> from = $task -> start;
            }
            if($task -> to == '0000-00-00 00:00:00'){
                $task -> to = (($task -> repeat == 'none') ?  $task -> end : \Carbon::now()->endOfYear());
            }
            $task -> update();
        }
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
