<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConsumers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('consumers'))
        {
            return;
        }

		Schema::create('consumers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('postcode');
            $table->string('country');
			$table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unique(['user_id', 'email']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('consumers', function(Blueprint $table)
        {
            $table->dropForeign('consumers_user_id_foreign');
        });
		Schema::drop('consumers');
	}

}
