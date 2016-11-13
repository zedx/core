<?php

return merge_trans('email', [

    'admin' => [
        'payment_received' => [
            'subject' => 'Payment received',
            'content' => 'You have just received :amount :currency via :gateway',
        ],
        'user_created' => [
            'subject' => 'New user was registered',
            'content' => ':user create an account',
        ],
        'ad_created' => [
            'subject' => 'New ad was created',
            'content' => ":user vient d'ajouter l'annonce :ad",
        ],
        'ad_updated' => [
            'subject' => 'An ad was updated',
            'content' => ':user update the ad [ :ad ]',
        ],
        'ad_renew_request' => [
            'subject' => 'An Ad is waiting for renew',
            'content' => ':user just request a renewal for :ad',
        ],
        'adtype_purchased' => [
            'subject' => 'An Adtype was purchased',
            'content' => ':user just bought an Adtype [ :adtype ]',
        ],
        'subscription_purchased' => [
            'subject' => 'A subscription was purchased',
            'content' => ':user just bought a subscription [ :subscription ]',
        ],
        'password_reset' => [
            'subject' => 'Password reset',
            'content' => 'Click this link to reset your password: <a href=":link"> :link </a>',
        ],
    ],
    'user' => [
        'ad_validated' => [
            'subject' => 'Ad validated',
            'content' => 'Your Ad :ad is validated',
        ],
        'ad_refused' => [
            'subject' => 'Ad refused',
            'content' => 'Your Ad :ad has been refused for the following reasons :',
        ],
        'ad_deleted' => [
            'subject' => 'Ad deleted',
            'content' => 'Your Ad :ad has been deleted',
        ],
        'ad_expired' => [
            'subject' => 'Ad expired',
            'content' => 'Your Ad :ad is expired',
        ],
        'adtype_changed' => [
            'subject' => 'Ad type changed',
            'content' => 'Your Ad :ad became a :adtype ad',
        ],
        'adtype_purchased' => [
            'subject' => 'Ad type purchased',
            'content' => 'You have purchased :number :adtype ad(s)',
        ],
        'subscription_activated' => [
            'subject' => 'Subscription activated',
            'content' => 'You just subscribe :subscription subscription',
        ],
        'password_reset' => [
            'subject' => 'Password reset',
            'content' => 'Click this link to reset your password: <a href=":link"> :link </a>',
        ],
        'contact_user' => [
            'subject' => 'Your ":ad_title" ad on :website_title',
            'content' => '
:message
<br />
Contact Details :
Name : :sender_name
Email : :sender_email
Phone : :sender_phone
<hr />
This email was sent to you about your Ad "ad_title" you published on :website_title : <a href=":ad_url">:ad_url</a>',
        ],
    ],
]);
