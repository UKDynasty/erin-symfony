<?php
namespace App\Service;

use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;

class ESPNRosterManager
{
    /**
     * @var ESPN
     */
    private $espn;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ESPN $espn, EntityManagerInterface $entityManager)
    {
        $this->espn = $espn;
        $this->entityManager = $entityManager;
    }

    public function updateRosterCounts(): void
    {
        $rosters = $this->espn->getRosterInfo();
        foreach ($rosters as $espnRosterInfo) {
            $regularCount = 0;
            $irCount = 0;
            foreach($espnRosterInfo['slots'] as $slot) {
                if (!isset($slot['player'])) {
                    continue;
                }

                if ($slot['slotCategoryId'] === 21) {
                    ++$irCount;
                } else {
                    ++$regularCount;
                }
            }
            $franchise = $this->entityManager->getRepository(Franchise::class)->findOneBy([
                'espnFranchiseId' => $espnRosterInfo['teamId']
            ]);
            if (!$franchise) {
                throw new \Exception('Non-existent franchise with espn ID ' . $espnRosterInfo['teamId']);
            }
            $franchise->setEspnRosterCountRegular($regularCount);
            $franchise->setEspnRosterCountIR($irCount);
            $franchise->setEspnRosterCountTotal($regularCount+$irCount);
        }
        $this->entityManager->flush();
    }
}