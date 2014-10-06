<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbBookingMenu extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rb_booking_menu', function(Blueprint $table)
		{
            $table->increments('id');
			$table->unsignedInteger('booking_id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('user_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('rb_bookings')
                ->onDelete('cascade');
            $table->foreign('menu_id')
                ->references('id')
                ->on('rb_menus')
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
		Schema::table('rb_booking_menu', function(Blueprint $table)
        {
            $table->dropForeign('rb_booking_menu_booking_id_foreign');
            $table->dropForeign('rb_booking_menu_menu_id_foreign');
            $table->dropForeign('rb_booking_menu_user_id_foreign');
        });
        Schema::drop('rb_booking_menu');
	}

}
