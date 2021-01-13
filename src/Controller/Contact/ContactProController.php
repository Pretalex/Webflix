<?php

namespace App\Controller\Contact;

use App\Entity\ContactPro;
use App\Repository\ContactProRepository;
use App\Service\EmailService;
use DateTime;
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
            $contactPro = (new ContactPro())
                ->setFirstname($request->request->get('firstname'))
                ->setLastname($request->request->get('lastname'))
                ->setCompany($request->request->get('lastname'))
                ->setSubject($request->request->get('subject'))
                ->setEmail($request->request->get('email'))
                ->setMessage($request->request->get('message'))
                ->setCreatedAt(new DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contactPro);
            $em->flush();

            $context = [ 'contactPro' => $contactPro ];

            # Je m'envoie un email
            $emailService->send([
                'replyTo' => $contactPro->getEmail(),
                'subject' => $contactPro->getSubject(),
                'template' => 'email/contact_pro.email.twig',
                'context' => $context,
            ]);

            # Email de confirmation
            $emailService->send([
                'to' => $contactPro->getEmail(),
                'subject' => "Nous avons bien reçu votre message",
                'template' => 'email/contact_pro_confirmation.email.twig',
                'context' => $context,
            ]);

            $this->addFlash('success', "Nous avons bien reçu votre message.");
            return $this->redirectToRoute('contact_pro');
        }

        return $this->render('contact/contact_pro.html.twig');
    }

    /**
     * @Route("/contact/pro-admin", name="contact_pro_admin")
     */
    public function contactProAdmin(ContactProRepository $contactProRepository, Request $request) {
        // $start = new \DateTime('2021/01/04');
        // $end = new \DateTime('2021/01/06');
        // $contactPros = $contactProRepository->findContactsBetweenTwoDates($start, $end);

        $search = $request->query->get('search');
        if ($search) {
            $contactPros = $contactProRepository->search($search);
        } else {
            $contactPros = $contactProRepository->findAll();
        }

        return $this->render('contact/contact_pro_admin.html.twig', [
            'contactPros' => $contactPros
        ]);
    }
}
