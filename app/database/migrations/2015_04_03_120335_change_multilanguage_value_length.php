<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMultilanguageValueLength extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('multilanguage', function (Blueprint $table) {
            $table->renameColumn('value', 'old_value');
        });
    }

    /**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
        Schema::table('multilanguage', function (Blueprint $table) {
            //
        });
    }

}
