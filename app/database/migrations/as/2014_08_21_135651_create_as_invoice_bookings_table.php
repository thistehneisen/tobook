<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsInvoiceBookingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_invoice_bookings', function(Blueprint $table)
        {
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('booking_id');
            $table->primary(array('invoice_id', 'booking_id'));
            $table->foreign('booking_id')
                ->references('id')
                ->on('as_bookings')
                ->onDelete('cascade');
            $table->foreign('invoice_id')
                ->references('id')
                ->on('as_invoices')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('as_invoice_bookings');
    }

}
