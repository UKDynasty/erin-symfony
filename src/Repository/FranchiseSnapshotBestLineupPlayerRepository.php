<?php

namespace App\Repository;

use App\Entity\FranchiseSnapshotBestLineupPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FranchiseSnapshotBestLineupPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method FranchiseSnapshotBestLineupPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method FranchiseSnapshotBestLineupPlayer[]    findAll()
 * @method FranchiseSnapshotBestLineupPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FranchiseSnapshotBestLineupPlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FranchiseSnapshotBestLineupPlayer::class);
    }

    // /**
    //  * @return FranchiseSnapshotBestLineupPlayer[] Returns an array of FranchiseSnapshotBestLineupPlayer objects
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
    public function findOneBySomeField($value): ?FranchiseSnapshotBestLineupPlayer
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
