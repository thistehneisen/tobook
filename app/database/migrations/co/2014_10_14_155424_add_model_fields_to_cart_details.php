<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModelFieldsToCartDetails extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_details', function(Blueprint $table)
        {
            $table->string('model_type')->after('cart_id');
            $table->unsignedInteger('model_id')->after('model_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_details', function(Blueprint $table)
        {
            $table->dropColumn(['model_type', 'model_id']);
        });
    }

}
