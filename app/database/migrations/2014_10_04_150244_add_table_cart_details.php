<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableCartDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_details', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('cart_id');
            $table->unsignedInteger('item');
            $table->string('variant');
            $table->double('price');
            $table->double('quantity');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
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
		Schema::drop('cart_details');
	}

}
