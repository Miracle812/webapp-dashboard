<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkflowTables extends Migration {

	public function up()
	{
        Schema::create('workflow_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('author_id')   ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('author_id')   ->references('id')->on('users')->onDelete('set null');
            $table->string      ('title');
            $table->text        ('description') ->nullable()->default(NULL);

            $table->string      ('priority');
            $table->string      ('contact_type');

            $table->text        ('assigned_sites')->nullable()->default(NULL);
            $table->text        ('assigned_officers')->nullable()->default(NULL);

            $table->string      ('target');
            $table->string      ('repeat',10)   ->nullable()->default(NULL);
            $table->TinyInteger ('frequency')   ->nullable()->default(NULL);
            $table->string      ('weekend',3)   ->nullable()->default(NULL);
            $table->timestamps();
        });

        Schema::create('workflow_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('site_id')  ->unsigned();
            $table->foreign('site_id')  ->references('id')->on('units')->onDelete('CASCADE');
            $table->integer('task_id')  ->unsigned();
            $table->foreign('task_id')  ->references('id')->on('workflow_tasks')->onDelete('CASCADE');
            $table->date('date');
            $table->datetime('expiry');
        });

        Schema::create('workflow_completed', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('task_id')     ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('task_id')     ->references('id')->on('workflow_tasks')->onDelete('set null');
            $table->string      ('title');
            $table->text        ('description') ->nullable()->default(NULL);
            $table->date        ('date');

            $table->integer     ('site_id')     ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('site_id')     ->references('id')->on('units')->onDelete('set null');
            $table->string      ('site')        ->nullable()->default(NULL);

            $table->integer     ('author_id')   ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('author_id')   ->references('id')->on('users')->onDelete('set null');
            $table->string      ('author')      ->nullable()->default(NULL);

            $table->integer     ('officer_id')  ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('officer_id')  ->references('id')->on('users')->onDelete('set null');
            $table->string      ('officer')     ->nullable()->default(NULL);

            $table->text        ('summary')     ->nullable()->default(NULL);
            $table->TinyInteger ('completed')   ->default(0);
            $table->timestamps();
        });

        Schema::create('workflow_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('task_id')     ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('task_id')     ->references('id')->on('workflow_tasks')->onDelete('set null');
            $table->integer     ('item_id')     ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('item_id')     ->references('id')->on('workflow_items')->onDelete('set null');
            $table->integer     ('assigned_id') ->unsigned()->nullable()->default(NULL);
            $table->foreign     ('assigned_id') ->references('id')->on('users')->onDelete('set null');
            $table->string      ('action');
            $table->text        ('message');
            $table->softDeletes();
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
