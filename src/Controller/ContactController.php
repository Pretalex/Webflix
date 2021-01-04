<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(EmailService $emailService): Response
    {
        $email = "pierre@gmail.com";
        $message = "bonjour, super votre blog !";

        $emailService->send([
            'replyTo' => $email,
            'message' => $message,
            'subject' => "email.contact.subject",
            'template' => 'email/contact.email.twig',
        ]);

        return $this->render('contact/index.html.twig', [

        ]);
    }
}
