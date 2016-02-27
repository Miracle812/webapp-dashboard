<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTimezoneNewCleaningSchedule extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if(!Schema::hasColumn('new_cleaning_schedules_tasks', 'tz'))
            Schema::table('new_cleaning_schedules_tasks', function($table)
            {

                $table->String('tz')->nullable()->default(NULL);
            });
        if(!Schema::hasColumn('check_list_tasks', 'tz'))
            Schema::table('check_list_tasks', function($table)
            {
                $table->String('tz')->nullable()->default(NULL);
            });

        Schema::create('new_compliance_diary_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('unit_id')     ->unsigned();
            $table->foreign     ('unit_id')     ->references('id')->on('units')->onDelete('cascade');
            $table->integer     ('staff_id')    ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('staff_id')    ->references('id')->on('staffs')->onDelete('cascade');
            $table->string      ('title');
            $table->text        ('description') ->nullable()->default(NULL);
            $table->string      ('type',10);
            $table->timestamp   ('start')       ->default('0000-00-00 00:00:00');
            $table->timestamp   ('end')         ->default('0000-00-00 00:00:00');
            $table->String      ('tz')          ->nullable()->default(NULL);
            $table->string      ('repeat',10)   ->nullable()->default(NULL);
            $table->TinyInteger ('repeat_freq') ->nullable()->default(NULL);
            $table->timestamp   ('repeat_to')   ->nullable()->default(NULL);
            $table->TinyInteger ('weekend')     ->default(0);
            $table->TinyInteger ('all_day')     ->default(1);
            $table->TinyInteger ('status')      ->default(0);
            $table->timestamps();
        });

        Schema::create('new_compliance_diary_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('new_compliance_diary_tasks')->onDelete('cascade');
            $table->timestamp('start')->default('0000-00-00 00:00:00');
            $table->timestamp('end')->default('0000-00-00 00:00:00');
            $table->timestamp('expiry')->default('0000-00-00 00:00:00');
            $table->timestamps();
        });

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */

}
