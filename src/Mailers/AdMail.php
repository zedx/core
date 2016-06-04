<?php

namespace ZEDx\Mailers;

class AdMail extends Mailer
{
    public function created($user, $data)
    {
        return $this->sendMail($user, 'ad_created', $data);
    }

    public function updated($user, $data)
    {
        return $this->sendMail($user, 'ad_updated', $data);
    }

    public function renewRequest($user, $data)
    {
        return $this->sendMail($user, 'ad_renew_request', $data);
    }

    public function validated($user, $data)
    {
        return $this->sendMail($user, 'ad_validated', $data);
    }

    public function refused($user, $data)
    {
        return $this->sendMail($user, 'ad_refused', $data);
    }

    public function deleted($user, $data)
    {
        return $this->sendMail($user, 'ad_deleted', $data);
    }

    public function expired($user, $data)
    {
        return $this->sendMail($user, 'ad_expired', $data);
    }

    public function contactUser($user, $data, $dataSubject)
    {
        return $this->sendMail($user, 'contact_user', $data, $dataSubject);
    }
}
