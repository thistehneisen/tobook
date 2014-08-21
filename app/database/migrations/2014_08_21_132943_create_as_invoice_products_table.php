<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsInvoiceProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_invoice_products', function(Blueprint $table)
        {
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('product_id');
            $table->tinyInteger('quantity');
            $table->double('unit_price');
            $table->double('amount');
            $table->primary(array('invoice_id', 'product_id'));
            $table->foreign('product_id')
                ->references('id')
                ->on('as_products')
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
        Schema::drop('as_invoice_products');
    }

}
