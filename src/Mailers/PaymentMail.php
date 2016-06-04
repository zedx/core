<?php

namespace ZEDx\Mailers;

class PaymentMail extends Mailer
{
    public function received($user, $data)
    {
        return $this->sendMail($user, 'payment_received', $data);
    }
}
