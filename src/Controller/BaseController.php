<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->mostViewArticles3();

        return $this->render('base/accueil.html.twig', [
            'articles' => $articles

        ]);
    }

    /**
     * @Route("/a-propos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig');
    }

    public function header($ROUTE_NAME, ArticleRepository $articleRepository)
    {
        // Requete SQL
        $articles = $articleRepository->mostViewArticles3();

        return $this->render('base/header.html.twig', [
            'articles' => $articles,
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
            return $this->redirectToRoute('user_dashboard');
        } else {
            return $this->redirectToRoute('accueil');
        }
    }
}
