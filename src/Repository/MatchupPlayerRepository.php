<?php

namespace App\Repository;

use App\Entity\MatchupFranchise;
use App\Entity\MatchupPlayer;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MatchupPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchupPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchupPlayer[]    findAll()
 * @method MatchupPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchupPlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MatchupPlayer::class);
    }

    public function findByMflPlayerIdAndMatchupFranchise(int $playerId, MatchupFranchise $matchupFranchise)
    {
        return $this->createQueryBuilder('matchup_player')
            ->join('matchup_player.player', 'player')
            ->where('player.externalIdMfl = :playerId')
            ->andWhere('matchup_player.matchupFranchise = :matchupFranchise')
            ->setParameter('playerId', $playerId)
            ->setParameter('matchupFranchise', $matchupFranchise)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return MatchupPlayer[] Returns an array of MatchupPlayer objects
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
    public function findOneBySomeField($value): ?MatchupPlayer
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
