<?php

namespace App\Entity;

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
}
