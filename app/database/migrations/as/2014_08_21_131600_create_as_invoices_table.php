<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsInvoicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_invoices', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->date('issue_date');
            $table->date('due_date');
            $table->double('total');
            $table->double('discount');
            $table->double('deposit');
            $table->double('amount_due');
            $table->double('shipping_cost');
            $table->double('tax');
            $table->string('currency');
            $table->string('billing_address');
            $table->string('billing_name');
            $table->string('billing_phone');
            $table->string('billing_zip');
            $table->string('shipping_address');
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->string('shipping_zip');
            $table->boolean('is_shipped');
            $table->string('notes');
            $table->string('status');
            $table->foreign('booking_id')
                ->references('id')
                ->on('as_bookings')
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
        Schema::drop('as_invoices');
    }

}
