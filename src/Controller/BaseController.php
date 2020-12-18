<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(): Response
    {
        return $this->render('base/accueil.html.twig');
    }

    public function header()
    {
        // Requete SQL
        $articles = [];
        $prenom = 'victor';

        return $this->render('base/header.html.twig', [
            'articles' => $articles,
            'prenom' => $prenom,
        ]);
    }
}
