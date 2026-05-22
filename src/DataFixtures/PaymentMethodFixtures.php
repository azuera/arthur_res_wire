<?php
// src/DataFixtures/PaymentMethodFixtures.php

namespace App\DataFixtures;

use App\Entity\PayementMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PaymentMethodFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $paymentMethods = [
            ['user_ref' => 1, 'type' => 'Visa', 'lastDigits' => '4242'],
            ['user_ref' => 1, 'type' => 'PayPal', 'lastDigits' => 'user1@paypal.com'],
            ['user_ref' => 2, 'type' => 'Mastercard', 'lastDigits' => '1234'],
            ['user_ref' => 3, 'type' => 'American Express', 'lastDigits' => '5678'],
            ['user_ref' => 4, 'type' => 'Visa', 'lastDigits' => '9012'],
        ];

        foreach ($paymentMethods as $index => $pmData) {
            $paymentMethod = new PayementMethod();
            $paymentMethod->setType($pmData['type']);
            $paymentMethod->setLastDigits($pmData['lastDigits']);

            $paymentMethod->setUser($this->getReference('user_' . $pmData['user_ref'], \App\Entity\User::class));

            $manager->persist($paymentMethod);
            $this->addReference('payment_method_' . $index, $paymentMethod);
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            UserFixtures::class,
        ];
    }
}
