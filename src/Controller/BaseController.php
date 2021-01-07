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

    /**
     * @Route("/a-propos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig');
    }

    public function header($ROUTE_NAME)
    {
        // Requete SQL
        $articles = [];
        $prenom = 'victor';

        return $this->render('base/header.html.twig', [
            'articles' => $articles,
            'prenom' => $prenom,
            'ROUTE_NAME' => $ROUTE_NAME,
        ]);
    }

    /**
     * @Route("/account-redirect", name="account_redirect")
     */
    public function accountRedirect() {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        } elseif ($this->isGranted('ROLE_USER')) {
            # ..
        } else {
            return $this->redirectToRoute('accueil');
        }
    }
}
