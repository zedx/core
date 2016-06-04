<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDashboardwidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboardwidgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace');
            $table->string('title');
            $table->text('config');
            $table->integer('position')->unsigned()->default(1);
            $table->tinyInteger('size')->unsigned()->default(6);
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
        Schema::drop('dashboardwidgets');
    }
}
