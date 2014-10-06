<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbBookingGroup extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rb_booking_group', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('rb_bookings')
                ->onDelete('cascade');
            $table->foreign('group_id')
                ->references('id')
                ->on('rb_groups')
                ->onDelete('cascade');
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
        Schema::table('rb_booking_group', function(Blueprint $table)
        {
            $table->dropForeign('rb_booking_group_booking_id_foreign');
            $table->dropForeign('rb_booking_group_group_id_foreign');
            $table->dropForeign('rb_booking_group_user_id_foreign');
        });
        Schema::drop('rb_booking_group');
    }

}
