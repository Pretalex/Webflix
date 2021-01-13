<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/espace-utilisateur/", name="user_dashboard")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [

        ]);
    }
}
