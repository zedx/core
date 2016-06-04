<?php

namespace ZEDx\Mailers;

class SubscriptionMail extends Mailer
{
    public function purchased($user, $data)
    {
        return $this->sendMail($user, 'subscription_purchased', $data);
    }

    public function activated($user, $data)
    {
        return $this->sendMail($user, 'subscription_activated', $data);
    }
}
