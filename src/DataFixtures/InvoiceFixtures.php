<?php
// src/DataFixtures/InvoiceFixtures.php

namespace App\DataFixtures;

use App\Entity\Invoice;
use App\Entity\ProductKey;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $invoices = [
            [
                'user_ref' => 1,
                'number' => 'INV-2024-001',
                'createdAt' => new \DateTimeImmutable('2024-01-15'),
                'totalAmount' => 89.98,
                'status' => 'paid',
                'payment_method_ref' => 0,
                'products' => [
                    ['product_ref' => 0, 'product_key' => 'WITCHER-3-KEY-001'],
                    ['product_ref' => 2, 'product_key' => 'GOW-RAGNAROK-001'],
                ]
            ],
            [
                'user_ref' => 2,
                'number' => 'INV-2024-002',
                'createdAt' => new \DateTimeImmutable('2024-02-20'),
                'totalAmount' => 59.99,
                'status' => 'paid',
                'payment_method_ref' => 2,
                'products' => [
                    ['product_ref' => 1, 'product_key' => 'CYBERPUNK-2077-KEY-001'],
                ]
            ],
            [
                'user_ref' => 3,
                'number' => 'INV-2024-003',
                'createdAt' => new \DateTimeImmutable('2024-03-10'),
                'totalAmount' => 69.99,
                'status' => 'pending',
                'payment_method_ref' => 3,
                'products' => [
                    ['product_ref' => 3, 'product_key' => 'ZELDA-TOTK-KEY-001'],
                ]
            ],
            [
                'user_ref' => 4,
                'number' => 'INV-2024-004',
                'createdAt' => new \DateTimeImmutable('2024-04-05'),
                'totalAmount' => 24.99,
                'status' => 'paid',
                'payment_method_ref' => 4,
                'products' => [
                    ['product_ref' => 4, 'product_key' => 'HADES-KEY-001'],
                ]
            ],
        ];

        foreach ($invoices as $invoiceData) {
            $invoice = new Invoice();
            $invoice->setNumber($invoiceData['number']);
            $invoice->setCreatedAt($invoiceData['createdAt']);
            $invoice->setTotalAmount($invoiceData['totalAmount']);
            $invoice->setStatus($invoiceData['status']);
            $invoice->setUser($this->getReference('user_' . $invoiceData['user_ref'], \App\Entity\User::class));
            $invoice->setPaymentMethod($this->getReference('payment_method_' . $invoiceData['payment_method_ref'], \App\Entity\PayementMethod::class));

            $manager->persist($invoice);

            // Créer les ProductKeys associées
            foreach ($invoiceData['products'] as $productData) {
                $productKey = new ProductKey();
                $productKey->setNumber($productData['product_key']);
                $productKey->setDatetime(new \DateTime());
                $productKey->setProduct($this->getReference('product_' . $productData['product_ref'], \App\Entity\Product::class));
                $productKey->setInvoice($invoice);

                $manager->persist($productKey);
                $invoice->addProductKey($productKey);
            }
        }

        $manager->flush();
    }

    public function getDependencies():array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
            PaymentMethodFixtures::class,
        ];
    }
}
