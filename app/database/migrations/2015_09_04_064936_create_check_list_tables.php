<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckListTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('check_list_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('unit_id')     ->unsigned();
            $table->foreign     ('unit_id')     ->references('id')->on('units')->onDelete('cascade');
            $table->integer     ('staff_id')    ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('staff_id')    ->references('id')->on('staffs')->onDelete('cascade');
            $table->integer     ('form_id')     ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('form_id')     ->references('id')->on('forms')->onDelete('cascade');
            $table->string      ('title');
            $table->text        ('description') ->nullable()->default(NULL);
            $table->string      ('type',10);
            $table->timestamp   ('start')       ->default('0000-00-00 00:00:00');
            $table->timestamp   ('end')         ->default('0000-00-00 00:00:00');
            $table->string      ('repeat',10)   ->nullable()->default(NULL);
            $table->TinyInteger ('repeat_freq') ->nullable()->default(NULL);
            $table->timestamp   ('repeat_to')   ->nullable()->default(NULL);
            $table->TinyInteger ('weekend')     ->default(0);
            $table->TinyInteger ('all_day')     ->default(1);
            $table->TinyInteger ('status')      ->default(0);
            $table->timestamps();
        });

        Schema::create('check_list_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('check_list_tasks')->onDelete('cascade');
            $table->timestamp('start')->default('0000-00-00 00:00:00');
            $table->timestamp('end')->default('0000-00-00 00:00:00');
            $table->timestamp('expiry')->default('0000-00-00 00:00:00');
            $table->timestamps();
        });
        Schema::create('check_list_submitted', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('unit_id')->unsigned();
            $table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer     ('task_item_id');
            $table->string      ('title');
            $table->text        ('description')->nullable()->default(NULL);
            $table->string      ('staff_name')->nullable()->default(NULL);
            $table->string      ('form_name')->nullable()->default(NULL);
            $table->integer     ('form_answer_id')->unsigned()->nullable()->default(NULL);
            $table->foreign     ('form_answer_id')->references('id')->on('forms_answers')->onDelete('cascade');
            $table->timestamp   ('start')->default('0000-00-00 00:00:00');
            $table->timestamp   ('end')->default('0000-00-00 00:00:00');
            $table->TinyInteger ('all_day')->default(1);
            $table->text        ('summary')->nullable()->default(NULL);
            $table->TinyInteger ('completed')->default(0);
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
