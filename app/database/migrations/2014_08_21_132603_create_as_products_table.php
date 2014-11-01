<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_products', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('product_category_id');
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('price');
            $table->double('tax');
            $table->foreign('product_category_id')
                ->references('id')
                ->on('as_product_categories')
                ->onDelete('cascade');
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
        Schema::drop('as_products');
    }

}
