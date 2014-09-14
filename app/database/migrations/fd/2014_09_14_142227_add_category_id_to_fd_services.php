<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdToFdServices extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fd_services', function(Blueprint $table)
        {
            $table->unsignedInteger('business_category_id');
            $table->foreign('business_category_id')
                ->references('id')
                ->on('business_categories')
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
        Schema::table('fd_services', function(Blueprint $table)
        {
            $table->dropForeign('fd_services_business_category_id_foreign');
            $table->dropColumn('business_category_id');
        });
    }

}
