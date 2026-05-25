<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, entity: User::class)]
class UserHashPasswordListener
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function prePersist(User $user, LifecycleEventArgs $args): void
    {

        if (empty($user->getRoles()) || $user->getRoles() === ['ROLE_USER']) {
            $user->setRoles(['ROLE_USER']);
        }

        $this->hashPassword($user);
    }

    public function preUpdate(User $user, LifecycleEventArgs $args): void
    {
        $this->hashPassword($user);
    }

    private function hashPassword(User $user): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
            $user->eraseCredentials();
        }
    }
}
