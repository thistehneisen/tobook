<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataTypeForContentMtSmsTable extends Migration {

	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	$queries = [
	        "ALTER TABLE `varaa_mt_sms` MODIFY `from_name` VARCHAR(11);",
	    ];

    	if (is_tobook()) {
	        $queries = [
	            "ALTER TABLE `varaa_mt_sms` MODIFY `content` VARCHAR(65536);",
	            "ALTER TABLE `varaa_mt_sms` MODIFY `from_name` VARCHAR(11);",
	        ];
    	}

        foreach ($queries as $sql) {
            DB::statement($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $queries = [
            "ALTER TABLE `varaa_mt_sms` MODIFY `from_name` VARCHAR(10);",
        ];

        if (is_tobook()) {
	        $queries = [
	            "ALTER TABLE `varaa_mt_sms` MODIFY `content` VARCHAR(160);",
	            "ALTER TABLE `varaa_mt_sms` MODIFY `from_name` VARCHAR(10);",
	        ];
    	}

        foreach ($queries as $sql) {
            DB::statement($sql);
        }
    }


}
