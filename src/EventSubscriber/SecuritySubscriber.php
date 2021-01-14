<?php

namespace App\EventSubscriber;

use App\Entity\Membre;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class SecuritySubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function onKernelRequest()
    {
        $membre = $this->security->getUser();
        if (!$membre instanceof Membre) {
            return;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
