<?php
// src/DataFixtures/PlatformFixtures.php

namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatformFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $platforms = [
            ['name' => 'Steam', 'type' => 'PC', 'url' => 'https://store.steampowered.com', 'systems' => ['Windows', 'Mac', 'Linux']],
            ['name' => 'PlayStation Store', 'type' => 'Console', 'url' => 'https://store.playstation.com', 'systems' => ['PS4', 'PS5']],
            ['name' => 'Xbox Store', 'type' => 'Console', 'url' => 'https://www.xbox.com', 'systems' => ['Xbox One', 'Xbox Series X/S']],
            ['name' => 'Nintendo eShop', 'type' => 'Console', 'url' => 'https://www.nintendo.com', 'systems' => ['Switch']],
            ['name' => 'Epic Games Store', 'type' => 'PC', 'url' => 'https://store.epicgames.com', 'systems' => ['Windows', 'Mac']],
            ['name' => 'GOG', 'type' => 'PC', 'url' => 'https://www.gog.com', 'systems' => ['Windows', 'Mac', 'Linux']],
        ];

        foreach ($platforms as $index => $platformData) {
            $platform = new Platform();
            $platform->setName($platformData['name']);
            $platform->setType($platformData['type']);
            $platform->setUrl($platformData['url']);
            $platform->setSystems($platformData['systems']);

            $manager->persist($platform);

            // Sauvegarde une référence pour les autres fixtures
            $this->addReference('platform_' . $index, $platform);
        }

        $manager->flush();
    }
}
