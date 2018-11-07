<?php
namespace App\Service;


use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;

class TaxiSquads
{
    /**
     * @var GoogleSheet
     */
    private $googleSheet;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(GoogleSheet $googleSheet, EntityManagerInterface $entityManager)
    {

        $this->googleSheet = $googleSheet;
        $this->entityManager = $entityManager;
    }

    public function updateCounts()
    {
        $counts = $this->googleSheet->getTaxiSquadsCounts();
        $franchises = $this->entityManager->getRepository(Franchise::class)->findAll();
        foreach($franchises as $franchise) {
            $identifier = str_replace(' ', '', strtolower($franchise->getName()));
            $count = $counts[$identifier];
            $franchise->setTaxiSquadCount($count);
        }
        $this->entityManager->flush();
    }
}