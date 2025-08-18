<?php

namespace App\Repository;

use App\Entity\Veterinarian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Veterinarian>
 */
class VeterinarianRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Veterinarian::class);
    }

    public function findByCrmv(string $crmv): ?Veterinarian
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.crmv = :crmv')
            ->setParameter('crmv', $crmv)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
