<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DraftPickRepository extends EntityRepository
{
    private function addJoinDraft(QueryBuilder $qb)
    {
        return $qb
            ->join("draftPick.draft", "draft")
        ;
    }

    private function addDefaultOrderBy(QueryBuilder $qb)
    {
        $qb = $this->addJoinDraft($qb);
        return $qb
            ->addOrderBy("draft.year", "ASC")
            ->addOrderBy("draftPick.round", "ASC")
            ->addOrderBy("draftPick.number", "ASC")
        ;
    }

    public function getUnusedPicksForFranchise(Franchise $franchise)
    {
        $qb = $this->createQueryBuilder("draftPick")
            ->andWhere("draftPick.owner = :franchise")
            ->setParameter("franchise", $franchise)
            ->andWhere("draftPick.player is NULL");

        return $this->addDefaultOrderBy($qb)->getQuery()->getResult();
    }
}
