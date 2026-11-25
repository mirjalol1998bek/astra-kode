<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

// foydalanuvchining "So'ngi faollik" vaqtini yangilab turadi
class ActivitySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLogin',
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }

    public function onLogin(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof User) {
            $this->touch($user);
        }
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        // har bir so'rovda bazaga yozmaslik uchun 1 minutda bir marta
        $last = $user->getLastActivity();
        if ($last && $last > new \DateTimeImmutable('-1 minute')) {
            return;
        }

        $this->touch($user);
    }

    private function touch(User $user): void
    {
        if (!$this->em->contains($user)) {
            return;
        }

        $user->setLastActivity(new \DateTimeImmutable());
        $this->em->flush();
    }
}
