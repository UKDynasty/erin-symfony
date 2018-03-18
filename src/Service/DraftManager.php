<?php
namespace App\Service;

use App\Entity\Draft;
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
}