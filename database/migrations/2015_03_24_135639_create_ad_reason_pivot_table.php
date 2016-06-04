<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdReasonPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_reason', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->integer('reason_id')->unsigned()->index();
            $table->foreign('reason_id')->references('id')->on('reasons')->onDelete('cascade');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ad_reason');
    }
}
