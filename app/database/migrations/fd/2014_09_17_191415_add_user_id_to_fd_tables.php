<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToFdTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fd_coupons', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('fd_flash_deals', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('fd_flash_deal_dates', function(Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
            $table->dropForeign('fd_coupons_user_id_foreign');
        });

        Schema::table('fd_coupons', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('fd_flash_deals', function ($table) {
            $table->dropForeign('fd_flash_deals_user_id_foreign');
        });

        Schema::table('fd_flash_deals', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('fd_flash_deal_dates', function ($table) {
            $table->dropForeign('fd_flash_deal_dates_user_id_foreign');
        });

        Schema::table('fd_flash_deal_dates', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }

}
