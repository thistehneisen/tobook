<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaMultilanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('multilanguage', function(Blueprint $table)
        {
            $table->unsignedInteger('user_id')->nullable();
            $table->string('context');
            $table->string('lang');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
            $table->index('context');
            $table->index('key');
            $table->index('lang');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
		Schema::drop('multilanguage');
	}

}
