<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteField extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('as_booking_extra_services', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_booking_payments', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_booking_services', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_bookings', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_consumers', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_employee_custom_time', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_employee_default_time', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_employee_freetime', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_employees', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_extra_services', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_invoice_bookings', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_invoice_products', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_invoices', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_options', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_product_categories', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_products', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_resources', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_service_categories', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_service_times', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('as_services', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('business_categories', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('consumers', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('images', function(Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('modules', function(Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('as_booking_extra_services', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_booking_payments', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_booking_services', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_bookings', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_consumers', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_employee_custom_time', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_employee_default_time', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_employee_freetime', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_employees', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_extra_services', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_invoice_bookings', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_invoice_products', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_invoices', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_options', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_product_categories', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_products', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_resources', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_service_categories', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_service_times', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('as_services', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('business_categories', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('consumers', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('images', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('modules', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }

}
