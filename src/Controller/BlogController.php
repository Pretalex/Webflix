<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Comment;
use App\Form\FilmType;
use App\Form\CommentType;
use App\Repository\FilmRepository;
use App\Repository\CommentRepository;
use App\Security\Voter\FilmVoter;
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
     * @Route("/films", name="blog")
     */
    public function blog(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findBlogfilms();

        return $this->render('blog/blog.html.twig', [
            'films' => $films
        ]);
    }

    /**
     * @Route("/film/{id}", name="film")
     */
    public function film(Film $film, Request $request): Response
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
     * @Route("/gestion-blog/mes-films", name="user_films")
     */
    public function userfilms() {
        return $this->render('blog/user_films.html.twig');
    }

    /**
     * @Route("/gestion-blog/film/{id}", name="blog_film_new")
     */
    public function newBlogfilm($id, Request $request, FilmRepository $filmRepository) {
        if ($id === 'nouveau') {
            $film = new film();
            $film->setAuthor($this->getUser());
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
                # On récupère l'image depuis le champ du formulaire
                $image = $form->get('image')->getData();
                if ($image) {
                    $path = $this->getParameter('uploads_path');

                    # On créé un nom unique
                    $nameParts = explode('.', $image->getClientOriginalName());
                    $uuid = Uuid::v6();
                    $newName = $nameParts[0].'-'.$uuid.'.'.$nameParts[1];

                    # On déplace l'image dans le dossier uploads
                    $image->move($path, $newName);

                    # On enregistre le nom de l'image, sur notre entité film
                    $film->setImage($newName);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($film);
                $em->flush();
                $this->addFlash('success', "L'film a bien été enregistré.");
                return $this->redirectToRoute('blog_film_new', [ 'id' => $film->getId() ]);
            } else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('blog/new_film.html.twig', [
            'form' => $form->createView(),
            'film' => $film,
        ]);
    }

    /**
     * @Route("/gestion-blog/supprimer-film/{id}", name="blog_film_delete")
     */
    public function deleteBlogfilm(Film $film)
    {
        $this->denyAccessUnlessGranted(FilmVoter::DELETE, $film);

        $em = $this->getDoctrine()->getManager();
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', "L'film a bien été supprimé.");
        return $this->redirectToRoute('blog');
    }

        /**
     * @Route("/comment_supprimer/{id}", name="comment_delete")
     */
    public function deleteComment(Comment $comment)
    {
        $film = $comment->getFilm();
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash('success', "Le commentaire à bien été supprimer.");
        return $this->redirectToRoute('film',[ 'id' => $film->getId() ]);
    }
}
