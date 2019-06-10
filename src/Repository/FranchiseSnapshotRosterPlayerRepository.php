<?php

namespace App\Repository;

use App\Entity\FranchiseSnapshotRosterPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FranchiseSnapshotRosterPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FranchiseSnapshotRosterPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FranchiseSnapshotRosterPlayer[]    findAll()
 * @method FranchiseSnapshotRosterPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FranchiseSnapshotRosterPlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FranchiseSnapshotRosterPlayer::class);
    }

    // /**
    //  * @return FranchiseSnapshotRosterPlayer[] Returns an array of FranchiseSnapshotRosterPlayer objects
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
    public function findOneBySomeField($value): ?FranchiseSnapshotRosterPlayer
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
