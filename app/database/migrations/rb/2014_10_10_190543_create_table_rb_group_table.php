<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbGroupTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rb_group_table', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('table_id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')
                ->references('id')
                ->on('rb_groups')
                ->onDelete('cascade');
            $table->foreign('table_id')
                ->references('id')
                ->on('rb_tables')
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
        Schema::table('rb_group_table', function(Blueprint $table)
        {
            $table->dropForeign('rb_group_table_group_id_foreign');
            $table->dropForeign('rb_group_table_table_id_foreign');
        });
        Schema::drop('rb_group_table');
    }

}
