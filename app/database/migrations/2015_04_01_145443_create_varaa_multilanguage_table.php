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
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('master_category_id')
                ->after('category_id')
                ->nullable();
            $table->string('context');
            $table->string('lang');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
            $table->index('context');
            $table->index('key');
            $table->index('lang');
            $table->unique(array('context', 'key', 'lang'));
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('master_category_id')
                ->references('id')
                ->on('as_master_categories')
                ->onDelete('set null');
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
