<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbBookings extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rb_bookings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uuid');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('consumer_id');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at');
            $table->float('total')->nullable();
            $table->string('status');
            $table->boolean('is_group_booking');
            $table->string('source');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('consumer_id')
                ->references('id')
                ->on('consumers')
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
        Schema::table('rb_bookings', function(Blueprint $table)
        {
            $table->dropForeign('rb_bookings_user_id_foreign');
            $table->dropForeign('rb_bookings_consumer_id_foreign');
        });
        Schema::drop('rb_bookings');
    }

}
