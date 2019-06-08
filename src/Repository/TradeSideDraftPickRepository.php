<?php

namespace App\Repository;

use App\Entity\TradeSideDraftPick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TradeSideDraftPick|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeSideDraftPick|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeSideDraftPick[]    findAll()
 * @method TradeSideDraftPick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeSideDraftPickRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TradeSideDraftPick::class);
    }

    // /**
    //  * @return TradeSideDraftPick[] Returns an array of TradeSideDraftPick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TradeSideDraftPick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
