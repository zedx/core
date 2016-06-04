<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWidgetnodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgetnodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('templateblock_id')->unsigned()->index();
            $table->foreign('templateblock_id')->references('id')->on('templateblocks');
            $table->integer('page_id')->unsigned()->index();
            $table->foreign('page_id')->references('id')->on('pages');
            $table->string('namespace');
            $table->string('title');
            $table->text('config');
            $table->integer('position')->unsigned()->index();
            $table->boolean('is_enabled')->default('1');
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
        Schema::drop('widgetnodes');
    }
}
