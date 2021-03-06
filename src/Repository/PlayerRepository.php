<?php

namespace App\Repository;

use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\Position;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param Franchise $franchise
     * @return Player[]|Collection
     */
    public function getPlayersForFranchiseOrdered(Franchise $franchise)
    {
        return $this->createQueryBuilder("player")
            ->join("player.position", "position")
            ->andWhere("player.franchise = :franchise")
            ->setParameter("franchise", $franchise)
            ->addOrderBy("position.priority", "DESC")
            ->addOrderBy('player.value', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Player[]|Collection
     */
    public function getFreeAgentsOrderedByValue()
    {
        return $this->createQueryBuilder("player")
            ->andWhere("player.franchise IS NULL")
            ->addOrderBy('player.value', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getPlayersForFranchiseByPositionOrderedByValue(Franchise $franchise, Position $position, int $limit = null)
    {
        $qb = $this->createQueryBuilder("player")
            ->andWhere('player.franchise = :franchise')
            ->andWhere('player.position = :position')
            ->setParameter('franchise', $franchise)
            ->setParameter('position', $position)
            ->orderBy('player.value', 'desc')
            ;

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function getPlayersForFranchiseByPositionsOrderedByValue(Franchise $franchise, array $positions, array $exceptionIds = [], int $limit = null)
    {
        $qb = $this->createQueryBuilder("player")
            ->andWhere('player.franchise = :franchise')
            ->join('player.position', 'position')
            ->andWhere('position.name IN (:positions)')
            ->setParameter('franchise', $franchise)
            ->setParameter('positions', $positions)
            ->orderBy('player.value', 'desc')
        ;

        if (count($exceptionIds) > 0) {
            $qb->andWhere('player.id NOT IN (:exceptionIds)');
            $qb->setParameter('exceptionIds', $exceptionIds);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Franchise $franchise
     * @return Player[]
     */
    public function getTradeBaitByFranchiseOrdered(Franchise $franchise)
    {
        return $this->createQueryBuilder("player")
            ->andWhere("player.franchise = :franchise")
            ->setParameter("franchise", $franchise)
            ->andWhere("player.listedAsTradeBait = :t")
            ->setParameter("t", true)
            ->join("player.position", "position")
            ->orderBy("position.priority", "DESC")
            ->getQuery()
            ->getResult();
    }

    public function getPlayersWithBirthdayToday()
    {
        $today = new \DateTime('now', new \DateTimeZone('UTC'));
        return $this->createQueryBuilder('player')
            ->andWhere('DAY(player.birthdate) = :day')
            ->andWhere('MONTH(player.birthdate) = :month')
            ->setParameter('day', $today->format('j'))
            ->setParameter('month', $today->format('n'))
            ->getQuery()
            ->getResult()
        ;
    }
}
