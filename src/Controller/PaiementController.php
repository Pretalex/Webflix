<?php

namespace App\Controller;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
