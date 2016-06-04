<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdcontentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adcontents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id')->unsigned()->index();
            $table->foreign('ad_id')->references('id')->on('ads');
            $table->string('title');
            $table->text('body');
            $table->nullableTimestamps();
            //$table->engine = 'MyISAM';
        });

        //DB::statement('ALTER TABLE '.DB::getTablePrefix().'adcontents ADD FULLTEXT search(title, body)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adcontents', function (Blueprint $table) {
            //$table->dropIndex('search');
            $table->drop();
        });
    }
}
