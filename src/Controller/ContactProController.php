<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactProController extends AbstractController
{
    /**
     * @Route("/contact/pro", name="contact_pro")
     */
    public function index(Request $request, EmailService $emailService): Response
    {
        if ($request->isMethod('POST')) {
            $data = [
                'mail' => $request->request->get('email'),
                'firstname' => $request->request->get('firstname'),
                'lastname' => $request->request->get('lastname'),
                'company' => $request->request->get('company'),
                'subject' => $request->request->get('subject'),
                'message' => $request->request->get('message'),
            ];

            # Je m'envoie un email
            $emailService->send([
                'replyTo' => $data['mail'],
                'subject' => $data['subject'],
                'template' => 'email/contact_pro.email.twig',
                'context' => $data,
            ]);

            # Email de confirmation
            $emailService->send([
                'to' => $data['mail'],
                'subject' => "Nous avons bien reçu votre message",
                'template' => 'email/contact_pro_confirmation.email.twig',
                'context' => $data,
            ]);

            $this->addFlash('success', "Nous avons bien reçu votre message.");
            return $this->redirectToRoute('contact_pro');
        }

        return $this->render('contact/contact_pro.html.twig');
    }
}
