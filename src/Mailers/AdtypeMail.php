<?php

namespace ZEDx\Mailers;

class AdtypeMail extends Mailer
{
    public function changed($user, $data)
    {
        return $this->sendMail($user, 'adtype_changed', $data);
    }

    public function purchased($user, $data)
    {
        return $this->sendMail($user, 'adtype_purchased', $data);
    }
}
