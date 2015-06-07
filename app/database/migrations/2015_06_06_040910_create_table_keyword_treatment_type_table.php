<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKeywordTreatmentTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_keyword_treatment_type', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('keyword');
            $table->unsignedInteger('treatment_type_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('treatment_type_id')
                ->references('id')
                ->on('as_treatment_types')
                ->onDelete('cascade');
             $table->unique(['keyword', 'treatment_type_id']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_keyword_treatment_type');
	}

}
