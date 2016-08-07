<?php

use Illuminate\Database\Seeder;
use ZEDx\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'website_name'                => 'ZEDx',
            'website_url'                 => 'https://demo.zedx.io',
            'website_title'               => 'Titre',
            'website_description'         => 'Description',
            'website_tracking_code'       => '',
            'language'                    => 'fr',
            'social_auths'                => json_encode($this->getDefaultProviders()),
            'currency'                    => 'EUR',
            'default_ad_currency'         => 'EUR',
            'ad_descr_min'                => 10,
            'ad_descr_max'                => 150,
            'tell_me_payment_received'    => 3,
            'tell_me_new_user'            => 3,
            'tell_me_new_ads'             => 3,
            'tell_me_edit_ads'            => 3,
            'tell_me_renew_ads'           => 3,
            'tell_me_payment_ads'         => 3,
            'tell_me_new_payment_subscr'  => 3,
            'tell_client_ad_accepted'     => 1,
            'tell_client_ad_refused'      => 1,
            'tell_client_ad_deleted'      => 1,
            'tell_client_ad_expired'      => 1,
            'new_user_welcome_message'    => 1,
            'tell_client_ad_type_changed' => 1,
            'tell_client_new_subscr'      => 1,
        ]);
    }

    protected function getDefaultProviders()
    {
        return [
            'facebook' => [
                'icon'       => 'facebook',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
            'github' => [
                'icon'       => 'github',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
            'google' => [
                'icon'       => 'google-plus',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
            'linkedin' => [
                'icon'       => 'linkedin',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
            'twitter' => [
                'icon'       => 'twitter',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
            'bitbucket' => [
                'icon'       => 'bitbucket',
                'client_id'  => '',
                'secret_key' => '',
                'enabled'    => false,
            ],
        ];
    }
}
