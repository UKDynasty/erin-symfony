<?php

namespace App\Repository;

use App\Entity\Franchise;
use App\Entity\Matchup;
use App\Entity\Week;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Matchup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matchup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matchup[]    findAll()
 * @method Matchup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Matchup::class);
    }

    /**
     * @param Week $week
     * @param Franchise|NULL $franchise
     * @return Matchup[]
     */
    public function findByWeek(Week $week, Franchise $franchise = null)
    {
        if ($franchise) {
            return $this->getEntityManager()->createQuery(
                sprintf(
                    'SELECT m FROM App\Entity\Matchup m INNER JOIN m.matchupFranchises mf JOIN m.week w JOIN mf.franchise f WHERE w.number = %s AND f.id = %s',
                    $week->getNumber(),
                    $franchise->getId()
                )
            )->getResult();
        }
        return $week->getMatchups()->toArray();
    }


    /*
    public function findOneBySomeField($value): ?Matchup
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
