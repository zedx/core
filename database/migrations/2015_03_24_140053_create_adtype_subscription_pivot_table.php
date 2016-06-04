<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdtypeSubscriptionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adtype_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adtype_id')->unsigned()->index();
            $table->foreign('adtype_id')->references('id')->on('adtypes')->onDelete('cascade');
            $table->integer('subscription_id')->unsigned()->index();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->integer('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('adtype_subscription');
    }
}
