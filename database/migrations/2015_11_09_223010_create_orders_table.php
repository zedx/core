<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status');
            $table->string('gatewayStatus');
            $table->string('name');
            $table->integer('quantity');
            $table->string('gateway');
            $table->string('driver');
            $table->decimal('amount', 11, 2);
            $table->integer('user_id');
            $table->integer('transaction_id')->unsigned()->index();
            $table->foreign('transaction_id')->references('id')->on('transactions');
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
        Schema::drop('orders');
    }
}
