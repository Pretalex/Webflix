<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Service\EmailService;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    private $encryptor;

    public function __construct(Encryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EmailService $emailService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            # Enregistrer en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $token = $this->encryptor->encrypt($user->getEmail());

            # Envoyer le mail
            $emailService->send([
                'to' => $user->getEmail(),
                'subject' => "Inscription sur mon site",
                'template' => 'email/confirmation_email.email.twig',
                'context' => [
                    'link' => $this->generateUrl('app_verify_email', [ 'token' => $token ], UrlGeneratorInterface::ABSOLUTE_URL)
                ],
            ]);

            $this->addFlash('success', "Votre inscription est prise en compte, merci de valider votre email.");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        AppAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler
    ): Response
    {
        $token = $request->query->get('token');
        $email = $this->encryptor->decrypt($token);
        $user = $userRepository->findOneBy([ 'email' => $email ]);
        if (!$user) {
            $this->createAccessDeniedException();
        }

        $user->setIsVerified(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', "Votre compte est bien validé :)");

        # Connecter automatiquement l'utilisateur
        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );
    }
}
