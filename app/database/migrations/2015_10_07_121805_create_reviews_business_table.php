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
		Schema::create('reviews', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('business_id');
            $table->string('name');
            $table->double('environment');
            $table->double('service');
            $table->double('price_ratio');
            $table->double('avg_rating'); 
            $table->string('status')->default('fresh');
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
		Schema::drop('reviews');
	}

}
