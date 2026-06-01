<?php


namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'title' => 'The Witcher 3: Wild Hunt',
                'quantity' => 100,
                'releaseDate' => new \DateTime('2015-05-19'),
                'region' => 'Worldwide',
                'price' => 29.99,
                'description' => 'Un RPG épique dans un monde fantastique ouvert.',
                'rating' => 4.9,
                'tags' => ['RPG', 'Aventure', 'Fantasy'],
                'requiredConfiguration' => 'OS: 64-bit Windows 7, CPU: Intel Core i5-2500K, RAM: 8GB, GPU: NVIDIA GeForce GTX 660',
                'platforms' => [0, 2] // Steam et Xbox Store
            ],
            [
                'title' => 'Cyberpunk 2077',
                'quantity' => 50,
                'releaseDate' => new \DateTime('2020-12-10'),
                'region' => 'Worldwide',
                'price' => 59.99,
                'description' => 'Un RPG futuriste dans un monde cyberpunk.',
                'rating' => 4.5,
                'tags' => ['RPG', 'Sci-Fi', 'Open World'],
                'requiredConfiguration' => 'OS: 64-bit Windows 10, CPU: Intel Core i7-4790, RAM: 16GB, GPU: NVIDIA GeForce RTX 2060',
                'platforms' => [0, 1, 2] // Steam, PlayStation, Xbox
            ],
            [
                'title' => 'God of War Ragnarök',
                'quantity' => 75,
                'releaseDate' => new \DateTime('2022-11-09'),
                'region' => 'Worldwide',
                'price' => 69.99,
                'description' => 'Un action-aventure nordique épique.',
                'rating' => 4.8,
                'tags' => ['Action', 'Aventure', 'Mythologie'],
                'requiredConfiguration' => 'PlayStation 4/5 exclusif',
                'platforms' => [1] // PlayStation uniquement
            ],
            [
                'title' => 'The Legend of Zelda: Tears of the Kingdom',
                'quantity' => 60,
                'releaseDate' => new \DateTime('2023-05-12'),
                'region' => 'Worldwide',
                'price' => 69.99,
                'description' => 'Une aventure épique dans Hyrule.',
                'rating' => 4.9,
                'tags' => ['Action', 'Aventure', 'Fantasy'],
                'requiredConfiguration' => 'Nintendo Switch exclusif',
                'platforms' => [3] // Nintendo eShop
            ],
            [
                'title' => 'Hades',
                'quantity' => 200,
                'releaseDate' => new \DateTime('2020-09-17'),
                'region' => 'Worldwide',
                'price' => 24.99,
                'description' => 'Un rogue-like dans les enfers grecs.',
                'rating' => 4.8,
                'tags' => ['Roguelike', 'Action', 'Indie'],
                'requiredConfiguration' => 'OS: Windows 7, CPU: Dual Core 2.4 GHz, RAM: 4GB, GPU: 1GB VRAM',
                'platforms' => [0, 4, 5] // Steam, Epic Games, GOG
            ],
        ];

        foreach ($products as $index => $productData) {
            $product = new Product();
            $product->setTitle($productData['title']);
            $product->setQuantity($productData['quantity']);
            $product->setReleaseDate($productData['releaseDate']);
            $product->setRegion($productData['region']);
            $product->setPrice($productData['price']);
            $product->setDescription($productData['description']);
            $product->setRating($productData['rating']);
            $product->setTags($productData['tags']);
            $product->setRequiredConfiguration($productData['requiredConfiguration']);

            // Associer les plateformes
            foreach ($productData['platforms'] as $platformRef) {

                $platform = $this->getReference('platform_' . $platformRef, \App\Entity\Platform::class);
                $product->addPlateform($platform);
            }

            $manager->persist($product);
            $this->addReference('product_' . $index, $product);
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            PlatformFixtures::class,
        ];
    }
}
