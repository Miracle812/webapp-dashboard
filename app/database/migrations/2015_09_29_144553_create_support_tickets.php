<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTickets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('support_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('support_assigned_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('support_categories')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('support_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('ident')->nullable()->default(NULL);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('support_categories')->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('title');
            $table->text('message')->nullable()->default(NULL);
            $table->TinyInteger ('status')->default(0);
            $table->timestamps();
        });

        Schema::create('support_replies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
            $table->integer('user_id')->nullable()->default(NULL);
            $table->string('user_name');
            $table->text('message');
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
