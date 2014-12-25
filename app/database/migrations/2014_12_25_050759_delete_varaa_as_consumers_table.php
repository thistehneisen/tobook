<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteVaraaAsConsumersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('as_consumers');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('as_consumers', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('consumer_id');
            $table->unsignedInteger('user_id'); // For faster retrieval
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('consumer_id')
                ->references('id')
                ->on('consumers')
                ->onDelete('cascade');
        });
	}

}
