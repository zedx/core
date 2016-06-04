<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdFieldPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_field', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->foreign('ad_id')->references('id')->on('ads');
            $table->integer('field_id')->unsigned()->index();
            $table->foreign('field_id')->references('id')->on('fields');
            $table->integer('value')->nullable();
            $table->string('string')->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_field');
    }
}
