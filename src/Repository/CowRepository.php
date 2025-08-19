<?php

namespace App\Repository;

use App\Entity\Cow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cow>
 */
class CowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cow::class);
    }

    public function findSlaughter() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM cow c
            WHERE c.slaughtered = 0
            AND (
                TIMESTAMPDIFF(YEAR, c.birth, CURRENT_DATE()) > 5
                OR ROUND(c.milk, 2) < 40
                OR (ROUND(c.milk, 2) < 70 AND (c.food / 7) > 50)
                OR c.weight > :arroba
            )
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['arroba' => 18 * 15]);

        return $result->fetchAllAssociative();
    }

    public function findTotalMilk() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT SUM(milk) FROM cow c
            WHERE c.slaughtered = 0
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchOne();
    }

    public function findTotalFood() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT SUM(food) FROM cow c
            WHERE c.slaughtered = 0
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchOne();
    }

    public function findTotalCowAndFood() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT COUNT(*) AS total_animals
                FROM cow
                WHERE food > 500
                AND TIMESTAMPDIFF(YEAR, birth, CURDATE()) < 1;
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchAssociative();
    }

    public function findSlaughtered() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM cow c
            WHERE c.slaughtered = 1
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    public function findCowAndFood() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM cow
                WHERE food > 500
                AND TIMESTAMPDIFF(YEAR, birth, CURDATE()) < 1;
        ";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }
}
