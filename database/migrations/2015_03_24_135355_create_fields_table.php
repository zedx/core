<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('type');
            $table->string('title');
            $table->string('unit');
            $table->boolean('is_price');
            $table->boolean('is_in_ad');
            $table->boolean('is_in_search');
            $table->boolean('is_format');
            $table->timestamp('cached_at')->useCurrent();
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
        Schema::drop('fields');
    }
}
