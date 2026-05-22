<?php
// src/DataFixtures/ImageFixtures.php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $images = [
            ['product_ref' => 0, 'url' => '/images/witcher3_1.jpg', 'altText' => 'The Witcher 3 cover', 'order' => 1],
            ['product_ref' => 0, 'url' => '/images/witcher3_2.jpg', 'altText' => 'Gameplay screenshot', 'order' => 2],
            ['product_ref' => 1, 'url' => '/images/cyberpunk_1.jpg', 'altText' => 'Cyberpunk 2077 cover', 'order' => 1],
            ['product_ref' => 2, 'url' => '/images/gow_1.jpg', 'altText' => 'God of War cover', 'order' => 1],
            ['product_ref' => 3, 'url' => '/images/zelda_1.jpg', 'altText' => 'Zelda cover', 'order' => 1],
            ['product_ref' => 4, 'url' => '/images/hades_1.jpg', 'altText' => 'Hades cover', 'order' => 1],
        ];

        foreach ($images as $imageData) {
            $image = new Image();
            $image->setUrl($imageData['url']);
            $image->setAltText($imageData['altText']);
            $image->setDisplayOrder($imageData['order']);
            $image->setProduct($this->getReference('product_' . $imageData['product_ref'], \App\Entity\Product::class));

            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
