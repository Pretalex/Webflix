<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwigController extends AbstractController
{
    /**
     * @Route("/twig", name="twig")
     */
    public function index(): Response
    {
        $prenom = 'Victor';
        $age = 28;

        return $this->render('twig/index.html.twig', [
            'controller_name' => 'TwigController',
            'age' => $age,
            'prenom' => $prenom,
        ]);
    }
}
