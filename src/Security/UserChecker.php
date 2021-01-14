<?php

namespace App\Security;

use App\Entity\Membre;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Membre) {
            return;
        }

        if (!$user->getEmailVerification()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException("Vous devez vérifier votre adresse email.");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Membre) {
            return;
        }

        // // user account is expired, the user may be notified
        // if ($user->isExpired()) {
        //     throw new AccountExpiredException('...');
        // }
    }
}
