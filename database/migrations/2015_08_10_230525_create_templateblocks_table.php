<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTemplateblocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templateblocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('template_id')->unsigned()->index();
            $table->foreign('template_id')->references('id')->on('templates');
            $table->string('identifier');
            $table->string('title');
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
        Schema::drop('templateblocks');
    }
}
