<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLcConsumers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lc_consumers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('consumer_id');
            $table->unsignedInteger('user_id'); // For faster retrieval
            $table->integer('score')->default(0);
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

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('lc_consumers', function(Blueprint $table)
        {
            $table->dropForeign('lc_consumers_user_id_foreign');
            $table->dropForeign('lc_consumers_consumer_id_foreign');
        });
		Schema::drop('lc_consumers');
	}

}
