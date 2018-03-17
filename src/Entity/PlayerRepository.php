<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

class PlayerRepository extends EntityRepository
{
    public function getPlayersForFranchiseOrdered(Franchise $franchise)
    {
        return $this->createQueryBuilder("player")
            ->join("player.position", "position")
            ->andWhere("player.franchise = :franchise")
            ->setParameter("franchise", $franchise)
            ->orderBy("position.priority", "DESC")
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
}
