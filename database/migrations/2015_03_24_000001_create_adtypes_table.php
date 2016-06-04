<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adtypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('is_headline')->default(false);
            $table->boolean('can_renew')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_update_pic')->default(false);
            $table->integer('nbr_pic')->default(0);
            $table->integer('nbr_days')->default(0);
            $table->boolean('can_add_video')->default(false);
            $table->integer('nbr_video')->default(0);
            $table->boolean('can_update_video')->default(false);
            $table->float('price')->default(0);
            $table->boolean('can_add_pic')->default(false);
            $table->boolean('is_customized')->default(false);
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
        Schema::drop('adtypes');
    }
}
