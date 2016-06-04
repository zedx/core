<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->unsigned()->index();
            $table->foreign('field_id')->references('id')->on('fields');
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->integer('step')->nullable();
            $table->boolean('is_smart')->default(0);
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
        Schema::drop('search_fields');
    }
}
