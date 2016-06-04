<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('actor_name');
            $table->integer('actor_id')->unsigned()->nullable();
            $table->string('actor_role')->nullable();
            $table->string('notified_name');
            $table->integer('notified_id')->unsigned()->nullable();
            $table->string('action')->nullable();
            $table->string('data')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_read')->default(0);
            $table->boolean('is_visible')->default(1);
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
        Schema::drop('notifications');
    }
}
