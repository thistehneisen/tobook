<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCategories extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('parent_id')->nullable();
            $table->string('keywords');
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
        Schema::drop('business_categories');
    }

}
