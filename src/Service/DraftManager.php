<?php
namespace App\Service;

use App\Entity\Draft;
use App\Entity\DraftPick;
use Doctrine\ORM\EntityManagerInterface;

class DraftManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }

    public function getCurrentDraft()
    {
        return $this->em->getRepository(Draft::class)->findCurrentDraft();
    }


    public function getPickOnClock(Draft $draft)
    {
        return $this->em->getRepository(DraftPick::class)->findPickOnClockForDraft($draft);
    }
}