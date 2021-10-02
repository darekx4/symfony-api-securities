<?php

namespace App\Repository;

use App\Entity\Facts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facts[]    findAll()
 * @method Facts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facts::class);
    }
}
