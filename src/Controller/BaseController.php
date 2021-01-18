<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\FilmRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(FilmRepository $filmRepository): Response
    {
        $films_nouveaux = $filmRepository->recherche('date_de_sortie','DESC',8);
        $films_plus_vus = $filmRepository->recherche('vus','DESC',8);
        $films_meilleur_note = $filmRepository->recherche('note_film','DESC',8);
        
        return $this->render('base/accueil.html.twig', [
            'films_nouveaux' => $films_nouveaux,
            'films_plus_vus' => $films_plus_vus,
            'films_meilleur_note' => $films_meilleur_note
        ]);
    }

    /**
     * @Route("/a-propos", name="apropos")
     */
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig');
    }

    public function header($ROUTE_NAME, GenreRepository $genreRepository)
    {
        // Requete SQL
        $genres = $genreRepository->findAll();

        return $this->render('base/header.html.twig', [
            'genres' => $genres,
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
            return $this->redirectToRoute('mon_compte');
        } else {
            return $this->redirectToRoute('accueil');
        }
    }
}
