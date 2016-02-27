<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MessagesSystem extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::drop('messages_recipients');
        Schema::drop('messages');

        Schema::create('messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('thread_id')   ->default(0);
            $table->integer     ('author_id')   ->unsigned();
            $table->foreign     ('author_id')   ->references('id')->on('users')->onDelete('cascade');
            $table->text        ('message');
            $table->TinyInteger ('status')      ->default(0);
            $table->timestamps();
        });

        Schema::create('messages_recipients', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments  ('id');
            $table->integer     ('message_id')->unsigned();
            $table->foreign     ('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->integer     ('user_id')   ->unsigned();
            $table->foreign     ('user_id')   ->references('id')->on('users')->onDelete('cascade');
            $table->TinyInteger ('status')    ->default(0);
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
