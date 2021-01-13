<?php

namespace App\Controller\Security;

use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Service\EmailService;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class PasswordController extends AbstractController
{
    const ENCRYPT_PREFIX = 'password-reset$';

    /**
     * @Route("/mot-de-passe-oublie", name="password_forgotten")
     */
    public function passwordForgotten(
        Request $request,
        UserRepository $userRepository,
        EmailService $emailService,
        Encryptor $encryptor
    ): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneByEmail($email);

            if ($user) {
                $token = $encryptor->encrypt(self::ENCRYPT_PREFIX.$user->getEmail());
                $link = $this->generateUrl('password_reset', [
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailService->send([
                    'to' => $user->getEmail(),
                    'subject' => "Réinitialiser votre email",
                    'template' => "email/password_forgotten.email.twig",
                    'context' => [
                        'link' => $link,
                        'user' => $user,
                    ],
                ]);
            }

            $this->addFlash('success', "Vous recevrez un email si votre adresse mail est bien existante.");
            return $this->redirectToRoute('password_forgotten');
        }

        return $this->render('password/password_forgotten.html.twig', [

        ]);
    }

    /**
     * @Route("/reinitialiser-mot-de-passe/{token}", name="password_reset")
     */
    public function passwordReset(
        $token,
        Encryptor $encryptor,
        UserRepository $userRepository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AppAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler
    ) {

        $decrypt = $encryptor->decrypt($token);
        $pos = strpos($decrypt, self::ENCRYPT_PREFIX);
        $email = str_replace(self::ENCRYPT_PREFIX, '', $decrypt);
        $user = $userRepository->findOneByEmail($email);

        if ($pos !== 0 || !$user) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encoder le mot de passe
            $plainPassword = $form->get('password')->getData();
            $encodedPassword = $passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Votre mot de passe a bien été réinitialisé.");

            # Connecter automatiquement l'utilisateur
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('password/password_reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
