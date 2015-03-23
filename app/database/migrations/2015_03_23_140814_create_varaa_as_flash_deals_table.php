<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaAsFlashDealsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_flash_deals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->double('discounted_price');
            $table->dateTime('expire');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
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
        Schema::table('as_flash_deals', function ($table) {
            $table->dropForeign('as_flash_deals_service_id_foreign');
        });
        Schema::drop('as_flash_deals');
    }

}
