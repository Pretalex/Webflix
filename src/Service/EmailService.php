<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private $mailer;
    private $adminEmail;
    private $env;

    public function __construct(MailerInterface $mailer, $adminEmail, $env) 
    {
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->env = $env;
    }

    /**
     * - to
     * - replyTo
     * - subject
     * - template
     * - context
     */
    public function send(array $data): bool {
        
        # Si pas de 'to', ou env = dev, on redirige sur ADMIN_EMAIL
        if ($this->env === 'dev' || !isset($data['to'])) {
            $to = $this->adminEmail;
        } else {
            $to = $data['to'];
        }
        
        $email = (new TemplatedEmail())
            ->from($this->adminEmail)
            ->to($to)
            ->replyTo($data['replyTo'] ?? $this->adminEmail)
            ->subject('WebFlix')
            ->htmlTemplate($data['template'])
            ->context($data['context'] ?? []);
        $this->mailer->send($email);

        return true;
    }
}
