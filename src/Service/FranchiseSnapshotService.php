<?php
namespace App\Service;

use App\Entity\Franchise;
use App\Entity\FranchiseSnapshot;
use App\Entity\FranchiseSnapshotRosterPlayer;
use App\Entity\Player;
use App\Repository\FranchiseSnapshotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class FranchiseSnapshotService
{
    const STARTING_LINEUP_REQUIREMENTS = [
        'QB' => 1,
        'RB' => 2,
        'WR' => 3,
        'TE' => 1,
    ];

    const FLEX_POSITIONS = ['RB', 'WR', 'TE'];
    const FLEX_SPOTS = 2;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generateSnapshot(Franchise $franchise): FranchiseSnapshot
    {
        $snapshot = new FranchiseSnapshot();
        $snapshot->setFranchise($franchise);
        $snapshot->setDate(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

        foreach($franchise->getPlayers() as $player) {
            $snapshotRosterPlayer = new FranchiseSnapshotRosterPlayer($player, $player->getValue() ?? 0);
            $snapshot->addToRoster($snapshotRosterPlayer);
        }
        $snapshot->setRosterCount(count($franchise->getPlayers()));

        $rosterTotalValue = array_reduce($franchise->getPlayers()->toArray(), function($value, Player $player) {
            return $value + $player->getValue();
        }, 0);

        $snapshot->setRosterValue($rosterTotalValue);
        $snapshot->setRosterValueAverage($rosterTotalValue / $snapshot->getRosterCount());



    }

    protected function findBestLineup(Franchise $franchise): array
    {
        $lineup = [];

        foreach(self::STARTING_LINEUP_REQUIREMENTS as $position => $numberRequired) {
            $criteria = Criteria::create()->andWhere(Criteria::expr()->eq('position', $position))->setMaxResults($numberRequired);
            $matchingPlayers = $franchise->getPlayers()->matching($criteria);
            foreach ($matchingPlayers as $matchingPlayer) {
                $lineup[] = $matchingPlayer;
            }
        }

        // Sort out flex positions
        $ids = array_map(function(Player``))
    }
}
