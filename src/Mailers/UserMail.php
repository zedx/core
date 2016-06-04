<?php

namespace ZEDx\Mailers;

class UserMail extends Mailer
{
    public function created($user, $data)
    {
        return $this->sendMail($user, 'user_created', $data);
    }
}
