<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableFdCoupons extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fd_coupons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->double('discounted_price');
            $table->date('valid_date');
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
        Schema::table('fd_coupons', function ($table) {
            $table->dropForeign('fd_coupons_service_id_foreign');
        });
        Schema::drop('fd_coupons');
    }

}
