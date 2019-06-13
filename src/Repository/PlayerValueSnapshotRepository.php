<?php

namespace App\Repository;

use App\Entity\PlayerValueSnapshot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlayerValueSnapshot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerValueSnapshot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerValueSnapshot[]    findAll()
 * @method PlayerValueSnapshot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerValueSnapshotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlayerValueSnapshot::class);
    }

    // /**
    //  * @return PlayerValueSnapshot[] Returns an array of PlayerValueSnapshot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayerValueSnapshot
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
