<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldHiddenWidgetToBusiness extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->boolean('is_booking_disabled', 0);
        });
    }

    /**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('is_booking_disabled');
        });
    }

}
