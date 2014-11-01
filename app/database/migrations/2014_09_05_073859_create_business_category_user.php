<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCategoryUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_category_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('business_category_id');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('business_category_id')
                ->references('id')->on('business_categories')
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
        Schema::table('business_category_user', function ($table) {
            $table->dropForeign('business_category_user_business_category_id_foreign');
            $table->dropForeign('business_category_user_user_id_foreign');
        });
        Schema::drop('business_category_user');
    }

}
