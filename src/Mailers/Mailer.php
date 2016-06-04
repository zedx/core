<?php

namespace ZEDx\Mailers;

use Exception;
use Mail;

abstract class Mailer
{
    /**
     * @var string
     */
    public $userType = 'user';

    public function admin()
    {
        $this->userType = 'admin';

        return $this;
    }

    public function user()
    {
        $this->userType = 'user';

        return $this;
    }

    public function sendMail($user, $mailType, $data, $dataSubject = [])
    {
        $view = 'emails.'.$this->userType.'.'.camel_case($mailType);
        $subject = trans('email.'.$this->userType.'.'.$mailType.'.subject', $dataSubject);

        return $this->sendTo($user, $subject, $view, $data);
    }

    public function sendTo($user, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function ($message) use ($user, $subject) {
                $message->to($user->email)
                    ->subject($subject);
            });
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
