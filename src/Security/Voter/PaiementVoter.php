<?php

namespace App\Security\Voter;

use App\Entity\Paiement;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PaiementVoter extends Voter
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const VOTE = 'vote';
    const DOWNLOAD = 'download';

    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [
                self::CREATE,
                self::UPDATE,
                self::DELETE,
                self::VOTE,
                self::DOWNLOAD,
            ])
            && $subject instanceof Paiement;
    }

    protected function voteOnAttribute($attribute, $paiement, TokenInterface $token)
    {
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate();
            case self::UPDATE:
                return $this->canUpdate();
            case self::DELETE:
                return $this->canDelete();
            case self::VOTE:
                return $this->canVote($paiement);
            case self::DOWNLOAD:
                return $this->canDownload($paiement);
        }

        return false;
    }

    public function canCreate(): bool
    {
        return $this->security->isGranted('ROLE_USER');
    }

    public function canUpdate(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function canDelete(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
    public function canVote(Paiement $paiement): bool
    {
        $user = $this->security->getUser();
        return $paiement->getMembre() === $user 
            && !$user->hasAlreadyVotedForFilm($paiement->getFilm());
    }
    public function canDownload(Paiement $paiement): bool
    {
        $user = $this->security->getUser();
        return $paiement->getMembre() === $user;
    }
}
