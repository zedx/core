<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_name', 'website_url', 'website_title',
        'website_description', 'website_tracking_code',
        'ad_descr_min', 'ad_descr_max', 'currency',
        'tell_me_new_user', 'tell_me_new_ads', 'tell_me_edit_ads',
        'tell_me_renew_ads', 'tell_me_payment_ads', 'tell_me_new_payment_subscr',
        'tell_client_ad_accepted', 'tell_client_ad_refused', 'tell_client_ad_deleted',
        'tell_client_ad_expired', 'tell_client_ad_type_changed', 'new_user_welcome_message',
        'tell_client_new_subscr', 'tell_me_payment_received', 'default_ad_currency',
        'social_auths', 'language',
    ];

    protected $casts = [
        'api_checked_at' => 'date',
    ];
}
