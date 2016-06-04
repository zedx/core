<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('page');
            $table->string('name');
            $table->string('shortcut')->unique();
            $table->text('description');
            $table->boolean('is_home')->default(false);
            $table->integer('template_id')->unsigned()->index();
            $table->foreign('template_id')->references('id')->on('templates');
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
        Schema::drop('pages');
    }
}
