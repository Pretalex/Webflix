<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Commentaire;
use App\Entity\Genre;
use App\Entity\Paiement;
use App\Form\FilmType;
use App\Form\CommentaireType;
use App\Repository\FilmRepository;
use App\Repository\CommentaireRepository;
use App\Security\Voter\FilmVoter;
use App\Security\Voter\PaiementVoter;
use App\Service\EmailService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class BlogController extends AbstractController
{
    /**
     * @Route("/films", name="films")
     */
    public function films(FilmRepository $filmRepository, Request $request): Response
    {
        $films = $filmRepository->search($request->query->all());
        return $this->render('blog/films.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/film/{id}", name="film")
     */
    public function film(Film $film): Response
    {
        $film->setVus($film->getVus() + 1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // $comment = new Comment();
        // $form = $this->createForm(CommentType::class, $comment);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $comment
        //         ->setCreatedAt(new DateTime())
        //         ->setAuthor($this->getUser())
        //         ->setFilm($film);

        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($comment);
        //     $em->flush();
        //     $this->addFlash('success', "Votre commentaire a bien été enregistré.");
        //     return $this->redirectToRoute('film',[ 'id' => $film->getId() ]);
        // }

        return $this->render('blog/film.html.twig', [
            'film' => $film,
            // 'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/nouveau_film/{id}", name="nouveau_film", defaults={"id":"nouveau"})
     */
    public function nouveauFilm($id, Request $request, FilmRepository $filmRepository) {
        if ($id === 'nouveau') {
            $film = new Film();
            $attribute = FilmVoter::CREATE;

        } else {
            $film = $filmRepository->find($id);
            if (!$film) {
                throw new NotFoundHttpException("film non trouvé !");
            }
            $attribute = FilmVoter::UPDATE;
        }

        $this->denyAccessUnlessGranted($attribute, $film);

        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // # On récupère l'image depuis le champ du formulaire
                // $image = $form->get('image')->getData();
                // if ($image) {
                //     $path = $this->getParameter('uploads_path');

                //     # On créé un nom unique
                //     $nameParts = explode('.', $image->getClientOriginalName());
                //     $uuid = Uuid::v6();
                //     $newName = $nameParts[0].'-'.$uuid.'.'.$nameParts[1];

                //     # On déplace l'image dans le dossier uploads
                //     $image->move($path, $newName);

                //     # On enregistre le nom de l'image, sur notre entité film
                //     $film->setImage($newName);
                // }

                $em = $this->getDoctrine()->getManager();
                $em->persist($film);
                $em->flush();
                $this->addFlash('success', "Le film a bien été enregistré.");
                return $this->redirectToRoute('films');
            } else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('blog/nouveau_film.html.twig', [
            'form' => $form->createView(),
            'film' => $film,
        ]);
    }

    /**
     * @Route("/supprimer-film/{id}", name="supprimer_film")
     */
    public function deleteBlogfilm(Film $film)
    {
        $this->denyAccessUnlessGranted(FilmVoter::DELETE, $film);

        $em = $this->getDoctrine()->getManager();
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', "Le film a bien été supprimé.");
        return $this->redirectToRoute('films');
    }

        /**
     * @Route("/commentaire_supprimer/{id}", name="commentaire_supprimer")
     */
    public function supprimerCommentaire(Commentaire $commentaire)
    {
        $film = $commentaire->getFilm();
        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();

        $this->addFlash('success', "Le commentaire à bien été supprimer.");
        return $this->redirectToRoute('film',[ 'id' => $film->getId() ]);
    }

        /**
     * @Route("/categorie/{id}", name="categorie")
     */
    public function categorie(Genre $genre, FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findByGenre($genre);
        return $this->render('blog/films.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/commentairenote/{id}", name="commentairenote")
     */
    public function commentairenote(Request $request, Paiement $paiement): Response
    {
        $this->denyAccessUnlessGranted(PaiementVoter::VOTE, $paiement);  

        $membre = $this->getUser();
        $commentaire = new Commentaire();
        $commentaire = $commentaire->setAuteur($membre);
        $commentaire = $commentaire->setFilm($paiement->getFilm());
        
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaire);
                $em->flush();
                $this->addFlash('success', "Votre commentaire a bien été pris en compte.");
                return $this->redirectToRoute('films');
            } else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('blog/commentairenote.html.twig', [
            'CommentaireForm' => $form->createView(),
            'membre' => $membre,
            'film' => $paiement->getFilm(),
        ]);
    }

}
