<?php

namespace ZEDx\Http\Requests;

class SettingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'website_name'                => 'required|min:3',
            'website_url'                 => 'required|url',
            'website_title'               => 'required|min:3',
            'website_description'         => 'required|min:3',
            'ad_descr_min'                => 'required|integer',
            'ad_descr_max'                => 'required|integer',
            'default_ad_currency'         => 'required|exists:countries,currency',
            'tell_me_new_user'            => 'required|integer|between:0,3',
            'tell_me_new_ads'             => 'required|integer|between:0,3',
            'tell_me_edit_ads'            => 'required|integer|between:0,3',
            'tell_me_renew_ads'           => 'required|integer|between:0,3',
            'tell_me_payment_ads'         => 'required|integer|between:0,3',
            'tell_me_new_payment_subscr'  => 'required|integer|between:0,3',
            'tell_me_payment_received'    => 'required|integer|between:0,3',
            'tell_client_ad_accepted'     => 'required|integer|between:0,3',
            'tell_client_ad_refused'      => 'required|integer|between:0,3',
            'tell_client_ad_deleted'      => 'required|integer|between:0,3',
            'tell_client_ad_expired'      => 'required|integer|between:0,3',
            'new_user_welcome_message'    => 'required|integer|between:0,3',
            'tell_client_ad_type_changed' => 'required|integer|between:0,3',
            'tell_client_new_subscr'      => 'required|integer|between:0,3',
        ];
    }
}
