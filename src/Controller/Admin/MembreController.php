<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Form\InscriptionType;
use App\Repository\FilmRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Security\Voter\MembreVoter;

class MembreController extends AbstractController
{
    /**
     * @Route("/mon_compte/", name="mon_compte")
     */
    public function index(): Response
    {
        return $this->render('membre/index.html.twig', [
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil( Request $request): Response
    {
        $membre = $this->getUser();
        
        if (!$membre) {
            throw new NotFoundHttpException("Forbidden !");
        }
        $attribute = MembreVoter::UPDATE;
        
        $this->denyAccessUnlessGranted($attribute, $membre);
        
        $form = $this->createForm(InscriptionType::class, $membre, [
            'whichform' => InscriptionType::FORM_PROFIL
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($membre);
                $em->flush();
                $this->addFlash('success', "Votre profil a bien été modifié.");
                return $this->redirectToRoute('mon_compte');
            } else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('membre/profil.html.twig', [
            'InscriptionForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("/mes_films/", name="mes_films")
     */
    public function mes_films(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->render('membre/mes_films.html.twig', [
            'films' => $films
        ]);
    }
}


