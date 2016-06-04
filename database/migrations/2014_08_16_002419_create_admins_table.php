<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('role_id')->unsigned()->index();
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            $table->string('linkedin_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('github_id')->nullable();
            $table->string('bitbucket_id')->nullable();
            $table->timestamp('cached_at')->useCurrent();
            $table->rememberToken();
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
        Schema::drop('admins');
    }
}
