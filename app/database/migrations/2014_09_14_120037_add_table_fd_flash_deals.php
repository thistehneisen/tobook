<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableFdFlashDeals extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fd_flash_deals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->double('discounted_price');
            $table->mediumInteger('quantity');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')
                ->references('id')
                ->on('fd_services')
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
        Schema::table('fd_flash_deals', function ($table) {
            $table->dropForeign('fd_flash_deals_service_id_foreign');
        });
        Schema::drop('fd_flash_deals');
    }

}
