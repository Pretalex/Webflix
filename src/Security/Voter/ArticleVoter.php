<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ArticleVoter extends Voter
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

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
            ])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate();
            case self::UPDATE:
                return $this->canUpdate();
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    public function canCreate(): bool
    {
        return !$this->security->isGranted('ROLE_ADMIN');
    }

    public function canUpdate(): bool
    {
        return true;
    }

    public function canDelete(): bool
    {
        return true;
    }
}
