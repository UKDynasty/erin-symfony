<?php

namespace App\Repository;

use App\Entity\FranchiseSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FranchiseSnapshot|null find($id, $lockMode = null, $lockVersion = null)
 * @method FranchiseSnapshot|null findOneBy(array $criteria, array $orderBy = null)
 * @method FranchiseSnapshot[]    findAll()
 * @method FranchiseSnapshot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FranchiseSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FranchiseSnapshot::class);
    }

    // /**
    //  * @return FranchiseSnapshot[] Returns an array of FranchiseSnapshot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FranchiseSnapshot
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
