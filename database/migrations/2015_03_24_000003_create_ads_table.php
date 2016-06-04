<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('adstatus_id')->unsigned()->index();
            $table->foreign('adstatus_id')->references('id')->on('adstatuses');
            $table->integer('adtype_id')->unsigned()->index();
            $table->foreign('adtype_id')->references('id')->on('adtypes');
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->bigInteger('views')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ads');
    }
}
