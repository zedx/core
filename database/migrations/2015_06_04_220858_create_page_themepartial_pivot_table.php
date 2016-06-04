<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageThemepartialPivotTable extends Migration
{
    /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::create('page_themepartial', function (Blueprint $table) {
      $table->integer('page_id')->unsigned()->index();
      $table->foreign('page_id')->references('id')->on('pages');
      $table->integer('themepartial_id')->unsigned()->index();
      $table->foreign('themepartial_id')->references('id')->on('themepartials');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::drop('page_themepartial');
  }
}
