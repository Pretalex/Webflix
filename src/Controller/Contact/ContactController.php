<?php

namespace App\Controller\Contact;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(EmailService $emailService, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $message = $request->request->get('message');

            $emailService->send([
                'replyTo' => $email,
                'subject' => "email.contact.subject",
                'template' => 'email/contact.email.twig',
                'context' => [
                    'mail' => $email,
                    'message' => $message
                ]
            ]);

            $this->addFlash('success', "Nous avons bien reçu votre message.");
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [

        ]);
    }
}
