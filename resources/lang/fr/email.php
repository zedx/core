<?php

return merge_trans('email', [

    'admin' => [
        'payment_received'       => [
            'subject' => '',
            'content' => 'Vous venez de recevoir la somme de :amount :currency via :gateway',
        ],
        'user_created'           => [
            'subject' => '',
            'content' => ":user vient de s'inscrire",
        ],
        'ad_created'             => [
            'subject' => '',
            'content' => ":user vient d'ajouter l'annonce :ad",
        ],
        'ad_updated'             => [
            'subject' => '',
            'content' => ":user vient de modifier l'annonce :ad",
        ],
        'ad_renew_request'       => [
            'subject' => '',
            'content' => ":user vient de faire une demande de renouvellement pour l'annonce :d",
        ],
        'adtype_purchased'       => [
            'subject' => '',
            'content' => ":user vient d'acheter une annonce de type :adtype",
        ],
        'subscription_purchased' => [
            'subject' => '',
            'content' => ':user vient de souscrire un abonnement payant [ :subscription ]',
        ],
        'password_reset'         => [
            'subject' => 'Réintialisation du mot de passe',
            'content' => 'Click here to reset your password: <a href=":link"> :link </a>',
        ],
    ],
    'user'  => [
        'ad_validated'           => [
            'subject' => '',
            'content' => "Votre annonce :ad vient d'être validée",
        ],
        'ad_refused'             => [
            'subject' => '',
            'content' => "Votre annonce :ad vient d'être refusée pour les raisons suivantes :",
        ],
        'ad_deleted'             => [
            'subject' => '',
            'content' => "Votre annonce :ad vient d'être supprimée",
        ],
        'ad_expired'             => [
            'subject' => '',
            'content' => "Votre annonce :ad vient d'expirer",
        ],
        'adtype_changed'         => [
            'subject' => '',
            'content' => 'Votre annonce :ad vient de passer en :adtype',
        ],
        'adtype_purchased'       => [
            'subject' => '',
            'content' => "Vous venez d'acheter :number annonce(s) de type :adtype",
        ],
        'subscription_activated' => [
            'subject' => '',
            'content' => "Vous venez de souscrire l'abonnement :subscription",
        ],
        'password_reset'         => [
            'subject' => 'Réintialisation du mot de passe',
            'content' => 'Click here to reset your password: <a href=":link"> :link </a>',
        ],
        'contact_user'           => [
            'subject' => 'Votre annonce ":ad_title" sur :website_title',
            'content' => '
:message
<br />
Coordonnées du contact :
Nom : :sender_name
Email : :sender_email
Tèl : :sender_phone
<hr />
Cet email vous a été envoyé au sujet de l\'annonce ":ad_title" que vous avez déposée sur :website_title : <a href=":ad_url">:ad_url</a>
<br /><p>PS : la personne qui vous a contacté ne connaîtra pas votre email tant que vous ne lui aurez pas répondu.</p>
            ',
        ],
    ],
]);
