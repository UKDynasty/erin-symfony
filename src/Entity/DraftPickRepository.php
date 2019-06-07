<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    public function getUnusedPicks()
    {
        $qb = $this->createQueryBuilder("draftPick")
            ->andWhere("draftPick.player is NULL");

        return $this->addDefaultOrderBy($qb)->getQuery()->getResult();
    }

    public function getUnusedPicksForFranchise(Franchise $franchise)
    {
        $qb = $this->createQueryBuilder("draftPick")
            ->andWhere("draftPick.owner = :franchise")
            ->setParameter("franchise", $franchise)
            ->andWhere("draftPick.player is NULL");

        return $this->addDefaultOrderBy($qb)->getQuery()->getResult();
    }

    public function getPickByYearRoundOriginalOwnerFranchise(int $year, int $round, Franchise $franchise)
    {
        $qb = $this->createQueryBuilder("draftPick")
            ->andWhere('draft.year = :year')
            ->andWhere("draftPick.originalOwner = :franchise")
            ->andWhere("draftPick.round = :round")
            ->setParameter("franchise", $franchise)
            ->setParameter("year", $year)
            ->setParameter("round", $round)
        ;

        return $this->addDefaultOrderBy($qb)->getQuery()->getOneOrNullResult();
    }

    public function findPickOnClockForDraft(Draft $draft)
    {
        try {
            return $this->createQueryBuilder('draft_pick')
                ->andWhere('draft_pick.draft = :draft')
                ->andWhere('draft_pick.player IS NULL')
                ->setParameter('draft', $draft)
                ->addOrderBy('draft_pick.overall', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
