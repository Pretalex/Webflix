<?php

namespace App\Controller\Security;

use App\Form\PasswordResetType;
use App\Repository\MembreRepository;
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
     * @Route("/mot-de-passe-oublie", name="mot_de_passe_oublie")
     */
    public function passwordForgotten(
        Request $request,
        MembreRepository $membreRepository,
        EmailService $emailService,
        Encryptor $encryptor
    ): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $membre = $membreRepository->findOneByEmail($email);

            if ($membre) {
                $token = $encryptor->encrypt(self::ENCRYPT_PREFIX.$membre->getEmail());
                $link = $this->generateUrl('mot_de_passe_reinitialise', [
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailService->send([
                    'to' => $membre->getEmail(),
                    'subject' => "Réinitialiser votre email",
                    'template' => "email/mot_de_passe_oublie.email.twig",
                    'context' => [
                        'link' => $link,
                        'membre' => $membre,
                    ],
                ]);
            }

            $this->addFlash('success', "Vous recevrez un email si votre adresse mail est bien existante.");
            return $this->redirectToRoute('mot_de_passe_oublie');
        }

        return $this->render('password/mot_de_passe_oublie.html.twig', [

        ]);
    }

    /**
     * @Route("/reinitialiser-mot-de-passe/{token}", name="mot_de_passe_reinitialise")
     */
    public function passwordReset(
        $token,
        Encryptor $encryptor,
        MembreRepository $MembreRepository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AppAuthenticator $authenticator,
        GuardAuthenticatorHandler $guardHandler
    ) {

        $decrypt = $encryptor->decrypt($token);
        $pos = strpos($decrypt, self::ENCRYPT_PREFIX);
        $email = str_replace(self::ENCRYPT_PREFIX, '', $decrypt);
        $membre = $MembreRepository->findOneByEmail($email);

        if ($pos !== 0 || !$membre) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->createForm(PasswordResetType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encoder le mot de passe
            $plainPassword = $form->get('mot_de_passe')->getData();
            $encodedPassword = $passwordEncoder->encodePassword($membre, $plainPassword);
            $membre->setMotDePasse($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', "Votre mot de passe a bien été réinitialisé.");

            # Connecter automatiquement l'utilisateur
            return $guardHandler->authenticateUserAndHandleSuccess(
                $membre,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('password/mot_de_passe_reinitialise.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}