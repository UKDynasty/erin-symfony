<?php

namespace App\Repository;

use App\Entity\TradeSidePlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TradeSidePlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeSidePlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeSidePlayer[]    findAll()
 * @method TradeSidePlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeSidePlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TradeSidePlayer::class);
    }

    // /**
    //  * @return TradeSidePlayer[] Returns an array of TradeSidePlayer objects
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
    public function findOneBySomeField($value): ?TradeSidePlayer
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
