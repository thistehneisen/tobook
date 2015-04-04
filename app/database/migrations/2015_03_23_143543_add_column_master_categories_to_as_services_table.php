<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMasterCategoriesToAsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('as_services', function (Blueprint $table) {
            $table->unsignedInteger('master_category_id')
                ->after('category_id')
                ->nullable();
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
        Schema::table('as_services', function (Blueprint $table) {
            $table->dropForeign('as_services_master_category_id_foreign');
            $table->dropColumn('master_category_id');
        });
    }
}
