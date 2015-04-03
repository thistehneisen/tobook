<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueInMultilanguage extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('multilanguage', function (Blueprint $table) {
            $table->dropUnique('multilanguage_context_key_lang_unique');
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
