<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class DraftRepository extends EntityRepository
{
    public function findCurrentDraft()
    {
        return $this->createQueryBuilder("draft")
            ->where('draft.complete = :complete')
            ->setParameter('complete', false)
            ->orderBy("draft.year", "ASC")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
