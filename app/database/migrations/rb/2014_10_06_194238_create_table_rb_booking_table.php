<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbBookingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rb_booking_table', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('table_id');
            $table->unsignedInteger('user_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('rb_bookings')
                ->onDelete('cascade');
            $table->foreign('table_id')
                ->references('id')
                ->on('rb_tables')
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
        Schema::table('rb_booking_table', function(Blueprint $table)
        {
            $table->dropForeign('rb_booking_table_booking_id_foreign');
            $table->dropForeign('rb_booking_table_table_id_foreign');
            $table->dropForeign('rb_booking_table_user_id_foreign');
        });
        Schema::drop('rb_booking_table');
    }

}
