<?php

namespace App\Repository;

use App\Entity\MatchupFranchise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MatchupFranchise|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchupFranchise|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchupFranchise[]    findAll()
 * @method MatchupFranchise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchupFranchiseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MatchupFranchise::class);
    }

    // /**
    //  * @return MatchupFranchise[] Returns an array of MatchupFranchise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MatchupFranchise
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
