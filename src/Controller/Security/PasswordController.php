<?php

namespace App\Controller\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    /**
     * @Route("/mot-de-passe-oublie", name="password_forgotten")
     */
    public function passwordForgotten(Request $request, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);
            dd($user);
        }

        return $this->render('password/password_forgotten.html.twig', [

        ]);
    }
}
