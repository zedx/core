<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('phone')->nullable();
            $table->boolean('is_phone')->default(false);
            $table->string('company')->nullable();
            $table->integer('role_id')->unsigned()->index();
            $table->integer('subscription_id')->unsigned()->index();
            $table->string('siret')->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->string('password', 60);
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            $table->string('linkedin_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('github_id')->nullable();
            $table->string('bitbucket_id')->nullable();
            $table->boolean('is_validate')->default(true);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('subscription_expired_at')->nullable();
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
        Schema::drop('users');
    }
}
