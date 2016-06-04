<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('website_name');
            $table->string('website_url');
            $table->string('website_title');
            $table->text('website_description');
            $table->text('website_tracking_code');
            $table->text('social_auths');
            $table->string('currency');
            $table->string('default_ad_currency');
            $table->tinyInteger('ad_descr_min');
            $table->tinyInteger('ad_descr_max');
            $table->tinyInteger('tell_me_payment_received');
            $table->tinyInteger('tell_me_new_user');
            $table->tinyInteger('tell_me_new_ads');
            $table->tinyInteger('tell_me_edit_ads');
            $table->tinyInteger('tell_me_renew_ads');
            $table->tinyInteger('tell_me_payment_ads');
            $table->tinyInteger('tell_me_new_payment_subscr');
            $table->tinyInteger('tell_client_ad_accepted');
            $table->tinyInteger('tell_client_ad_refused');
            $table->tinyInteger('tell_client_ad_deleted');
            $table->tinyInteger('tell_client_ad_expired');
            $table->tinyInteger('new_user_welcome_message');
            $table->tinyInteger('tell_client_ad_type_changed');
            $table->tinyInteger('tell_client_new_subscr');
            $table->string('language');
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
        Schema::drop('settings');
    }
}
