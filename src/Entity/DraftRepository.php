<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class DraftRepository extends EntityRepository
{
    public function findCurrentDraft()
    {
        return $this->createQueryBuilder("draft")
            ->orderBy("draft.year", "ASC")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
