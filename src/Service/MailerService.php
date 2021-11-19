<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(array $data = [])
    {
        /*$message = new \Swift_Message($data['subject']);
        $message->setFrom('josueprg@gmail.com');
        $message->setTo($data['email']);
        $message->setBody($view, 'text/html');

        return $this->mailer->send($message);*/
        $email = (new TemplatedEmail()) //new TemplatedEmail() or Email()
            ->from('josueprg@gmail.com')
            ->to($data['email'])
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($data['subject'])
            //->text('Sending emails is fun again!')
            //->html('<p>See Twig integration for better HTML integration!</p>');
            // path of the Twig template to render
            ->htmlTemplate('emails/new_user.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'name' => $data['name'].' Data Teste: '.new \DateTime('+7 days'),
                'email' => $data['email'],
            ]);

        $this->mailer->send($email);
    }
}