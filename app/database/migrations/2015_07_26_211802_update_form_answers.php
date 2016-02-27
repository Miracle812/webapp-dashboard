<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFormAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('forms_answers', function($table)
        {
            $table->string ('assigned',50)->nullable()->default(NULL);
        });

        Schema::create('forms_answers_updates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('unit_id')->unsigned();
            $table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->integer     ('answer_id')->unsigned();
            $table->foreign     ('answer_id')->references('id')->on('forms_answers')->onDelete('cascade');
            $table->text        ('changes')->nullable()->default(NULL);
            $table->text        ('comment')->nullable()->default(NULL);
            $table->text        ('signature')->nullable()->default(NULL);
            $table->string      ('name')->nullable()->default(NULL);
            $table->string      ('role')->nullable()->default(NULL);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE new_cleaning_schedules_tasks CHANGE description description TEXT NULL DEFAULT NULL;');
        DB::statement('ALTER TABLE new_cleaning_schedules_submitted CHANGE description description TEXT NULL DEFAULT NULL;');
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
