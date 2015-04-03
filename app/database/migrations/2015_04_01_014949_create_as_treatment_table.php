<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsTreatmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_treatment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_category_id')->nullable();
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('order');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('master_category_id')
                ->references('id')
                ->on('as_master_categories')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('as_treatment_types', function (Blueprint $table) {
            $table->dropForeign('as_treatment_types_master_category_id_foreign');
        });
        Schema::drop('as_treatment_types');
    }

}
