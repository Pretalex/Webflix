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

        $data = [
            'replyTo' => $email,
            'message' => $message
        ];

        $emailService->send($data);

        return $this->render('contact/index.html.twig', [

        ]);
    }
}
