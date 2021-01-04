<?php

namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;
    private $adminEmail;
    private $env;

    public function __construct(
        MailerInterface $mailer,
        $adminEmail,
        $env
    ) {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->env = $env;
    }

    public function send(array $data): bool {

        # Si pas de 'to', ou env = dev, on redirige sur ADMIN_EMAIL
        if ($this->env === 'dev' || !isset($data['to'])) {
            $to = $this->adminEmail;
        } else {
            $to = $data['to'];
        }

        $email = (new Email())
            ->from($this->adminEmail)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($data['replyTo'])
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Victor : Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

        return true;
    }
}
