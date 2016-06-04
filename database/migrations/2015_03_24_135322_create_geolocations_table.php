<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGeolocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geolocations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->foreign('ad_id')->references('id')->on('ads');
            $table->string('country')->nullable();
            $table->double('location_lat');
            $table->double('location_lng');
            $table->double('southwest_lat');
            $table->double('southwest_lng');
            $table->double('northeast_lat');
            $table->double('northeast_lng');
            $table->double('radius');
            $table->string('formatted_address');
            $table->string('json');
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
        Schema::drop('geolocations');
    }
}
