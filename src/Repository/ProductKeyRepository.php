<?php
// src/Repository/ProductKeyRepository.php

namespace App\Repository;

use App\Entity\ProductKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ProductKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductKey::class);
    }

    /**
     * @return QueryBuilder
     */
    public function findProductKeysWithoutInvoice(): QueryBuilder
    {
        return $this->createQueryBuilder('pk')
            ->where('pk.invoice IS NULL')
            ->orderBy('pk.id', 'ASC');
    }
}
