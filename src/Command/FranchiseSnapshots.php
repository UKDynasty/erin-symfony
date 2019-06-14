<?php

namespace App\Command;

use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\PlayerValueSnapshot;
use App\Service\FranchiseSnapshotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FranchiseSnapshots extends Command
{
    protected static $defaultName = 'app:franchisesnapshots';
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var FranchiseSnapshotService
     */
    private $franchiseSnapshotService;

    public function __construct(string $name = null, EntityManagerInterface $em, FranchiseSnapshotService $franchiseSnapshotService)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->franchiseSnapshotService = $franchiseSnapshotService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Save a snapshot of franchise roster stats')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $franchises = $this->em->getRepository(Franchise::class)->findAll();
        foreach($franchises as $franchise) {
            $snapshot = $this->franchiseSnapshotService->generateSnapshot($franchise);
            $this->em->persist($snapshot);
        }

        $this->em->flush();

        // Store player values too
        // TODO: break into separate command
        $players = $this->em->getRepository(Player::class)->findAll();
        $date = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        foreach($players as $player) {
            if (!$player->getValue()) {
                continue;
            }
            $playerValueSnapshot = new PlayerValueSnapshot();
            $playerValueSnapshot->setPlayer($player);
            $playerValueSnapshot->setValue($player->getValue());
            $playerValueSnapshot->setDate($date);
            $this->em->persist($playerValueSnapshot);
        }

        $this->em->flush();
    }
}
