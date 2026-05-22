<?php
// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'roles' => ['ROLE_ADMIN']
            ],
            [
                'email' => 'user1@example.com',
                'password' => 'user123',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'user2@example.com',
                'password' => 'user123',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'john.doe@example.com',
                'password' => 'john123',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'jane.smith@example.com',
                'password' => 'jane123',
                'roles' => ['ROLE_USER']
            ],
        ];

        foreach ($users as $index => $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setRoles($userData['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_' . $index, $user);
        }

        $manager->flush();
    }
}
