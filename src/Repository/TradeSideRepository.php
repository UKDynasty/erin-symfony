<?php

namespace App\Repository;

use App\Entity\TradeSide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TradeSide|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeSide|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeSide[]    findAll()
 * @method TradeSide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeSideRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TradeSide::class);
    }

    // /**
    //  * @return TradeSide[] Returns an array of TradeSide objects
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
    public function findOneBySomeField($value): ?TradeSide
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
