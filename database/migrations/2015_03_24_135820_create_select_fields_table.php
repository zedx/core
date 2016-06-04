<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSelectFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('select_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->unsigned()->index();
            $table->foreign('field_id')->references('id')->on('fields');
            $table->string('name');
            $table->integer('position');
            $table->timestamp('cached_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('select_fields');
    }
}
