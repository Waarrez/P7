<?php

namespace App\Events;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['encodePasswordUser', EventPriorities::PRE_WRITE]
        ];
    }

    public function encodePasswordUser(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Customer && $method === "POST") {
            $password = $result->getPassword();
            if (!empty($password)) {
                $hash = $this->hasher->hashPassword($result, $password);
                $result->setPassword($hash);
            } else {
                throw new \InvalidArgumentException('Le mot de passe ne peut pas être vide.');
            }
        }
    }
}
