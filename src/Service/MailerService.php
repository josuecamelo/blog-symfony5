<?php

namespace App\Service;

use Swift_Mailer;

class MailerService
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(array $data = [], $view)
    {
        $message = new \Swift_Message($data['subject']);
        $message->setFrom('josueprg@gmail.com');
        $message->setTo($data['email']);
        $message->setBody($view, 'text/html');

        return $this->mailer->send($message);
    }
}