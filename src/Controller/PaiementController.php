<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Paiement;
use DateTime;
use App\Security\Voter\PaiementVoter;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement/{id}", name="paiement")
     */
    public function index(Film $film): Response
    {
        return $this->render('paiement/paiement.html.twig', [
            'film' => $film,
        ]);
    }


    /**
     * @Route("/location_film/{id}", name="location_film")
     */
    public function locationFilm(EmailService $emailService, Film $film, Request $request)
    {
        $moyen_de_paiement = $request->query->get('moyen_de_paiement');
        $membre = $this->getUser();
        $paiement = new Paiement();
        $attribute = PaiementVoter::CREATE;

        // Vérification des droits
        $this->denyAccessUnlessGranted($attribute, $paiement);

        // Préparation des information a intégrer à la BDD
        $paiement->setMembre($membre);
        $paiement->setFilm($film);
        $paiement->setDatePaiement(new DateTime());

        // Intégration à la BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($paiement);
        $em->flush();
        
        // Envoi courriel de confirmation d'achat
        // Préparation du courriel
        $membre = $this->getUser();
        $email = $membre->getEmail();
        $sujet = 'Location Webflix';

        // Envoi du courriel
        $emailService->send([
            'replyTo' => $email,
            'subject' => $sujet,
            'template' => 'email/emailpaiement.email.twig',
            'context' => [
                'mail' => $email,
                'sujet' => $sujet,
                'film' => $film,
                'membre' => $membre
                ]
        ]);

        // Envoi du courriel de commantaire et notation
        // Préparation du courriel
        $membre = $this->getUser();
        $email = $membre->getEmail();
        $sujet = 'Commentaire et Notation Webflix';
        // Envoi du courriel
        $emailService->send([
            'replyTo' => $email,
            'subject' => $sujet,
            'template' => 'email/commentairenote.email.twig',
            'context' => [
                'mail' => $email,
                'sujet' => $sujet,
                'film' => $film,
                'membre' => $membre,
                'lien' => $this->generateUrl('commentairenote', ['id'=>$paiement->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                ]
        ]);
        
        // Redirection et Message flash
        $this->addFlash('success', "Votre location a bien été effectuée et un email de confirmation de location vous à été envoyé.");
        return $this->redirectToRoute('mon_compte');
    }
}


