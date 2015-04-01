<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaAsFlashDealsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_flash_deals', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('employee_id')->after('user_id');
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->double('discount_percentage')->after('discounted_price');
            $table->date('date')->after('discount_percentage');
            $table->time('start_at')->after('date');
            $table->time('end_at')->after('start_at');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees')
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
        Schema::table('as_flash_deals', function ($table) {
            $table->dropForeign('as_flash_deals_user_id_foreign');
        });

        Schema::table('as_flash_deals', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('as_flash_deals', function ($table) {
            $table->dropForeign('as_flash_deals_employee_id_foreign');
        });

        Schema::table('as_flash_deals', function(Blueprint $table) {
            $table->dropColumn('employee_id');
        });

        Schema::drop('as_flash_deals');
    }

}
