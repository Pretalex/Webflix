<?php

namespace App\Controller\Admin;

use App\Repository\CommentaireRepository;
use App\Repository\EmailMembreRepository;
use App\Repository\FilmRepository;
use App\Repository\GenreRepository;
use App\Repository\MembreRepository;
use App\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }

    /**
     * @Route("/admin_films", name="admin_films")
     */
    public function admin_films(FilmRepository $filmRepository, Request $request): Response
    {
        $films = $filmRepository->search($request->query->all());
        return $this->render('admin/admin_films.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/admin_genres", name="admin_genres")
     */
    public function admin_genres(GenreRepository $genreRepository, Request $request): Response
    {
        $genres = $genreRepository->findAll();
        return $this->render('admin/admin_genres.html.twig', [
            'genres' => $genres
        ]);
    }

    /**
     * @Route("/admin_membres", name="admin_membres")
     */
    public function admin_membres(MembreRepository $membreRepository, Request $request): Response
    {
        $membres = $membreRepository->findAll();
        return $this->render('admin/admin_membres.html.twig', [
            'membres' => $membres
        ]);
    }

    /**
     * @Route("/admin_emails", name="admin_emails")
     */
    public function admin_emails(EmailMembreRepository $emailMembreRepository, Request $request): Response
    {
        $emails = $emailMembreRepository->findAll();
        return $this->render('admin/admin_emails.html.twig', [
            'emails' => $emails
        ]);
    }

    /**
     * @Route("/admin_commentaires", name="admin_commentaires")
     */
    public function admin_commentaires(CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $commentaires = $commentaireRepository->findAll();
        return $this->render('admin/admin_commentaires.html.twig', [
            'commentaires' => $commentaires
        ]);
    }

    /**
     * @Route("/admin_paiements", name="admin_paiements")
     */
    public function admin_paiements(PaiementRepository $paiementRepository, Request $request): Response
    {
        $paiements = $paiementRepository->findAll();
        return $this->render('admin/admin_paiements.html.twig', [
            'paiements' => $paiements
        ]);
    }
}
