<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsBusinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_reviews', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('name');
            $table->tinyInteger('environment');
            $table->tinyInteger('service');
            $table->tinyInteger('price_ratio');
            $table->text('comment');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
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
		Schema::drop('as_reviews');
	}

}
