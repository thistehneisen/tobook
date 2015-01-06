<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaAsRoomServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_room_service', function(Blueprint $table)
        {
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('room_id');
            $table->primary(array('room_id', 'service_id'));
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
                ->onDelete('cascade');
            $table->foreign('room_id')
                ->references('id')
                ->on('as_rooms')
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
		Schema::drop('as_room_service');
	}

}
