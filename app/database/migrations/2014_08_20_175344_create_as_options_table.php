<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_options', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('key');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('group_id');
            $table->tinyInteger('order');
            $table->boolean('is_visible');
            $table->enum('control_type', array('text','select','radio','checkbox','calendar'));
            $table->enum('type', array('string','text','integer','float','boolean','datetime'));
            $table->string('name');
            $table->text('value');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('as_option_categories')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('as_option_groups')->onDelete('cascade');
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
		Schema::drop('as_options');
	}

}
