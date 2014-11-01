<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsExtraServiceServiceTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_extra_service_service', function(Blueprint $table)
        {
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('extra_service_id');
            $table->primary(array('extra_service_id', 'service_id'));
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
                ->onDelete('cascade');
            $table->foreign('extra_service_id')
                ->references('id')
                ->on('as_extra_services')
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
        Schema::drop('as_extra_service_service');
    }

}
