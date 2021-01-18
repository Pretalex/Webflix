<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Paiement;
use DateTime;
use App\Security\Voter\PaiementVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function locationFilm(Film $film, Request $request)
    {
        $moyen_de_paiement = $request->query->get('moyen_de_paiement');
        $membre = $this->getUser();
        $paiement = new Paiement();
        $attribute = PaiementVoter::CREATE;

        $this->denyAccessUnlessGranted($attribute, $paiement);

        $paiement->setMembre($membre);
        $paiement->setFilm($film);
        $paiement->setDatePaiement(new DateTime());


        $em = $this->getDoctrine()->getManager();
        $em->persist($paiement);
        $em->flush();
        $this->addFlash('success', "Votre location a bien été effectuée");
        return $this->redirectToRoute('mes_films');
    }
}

