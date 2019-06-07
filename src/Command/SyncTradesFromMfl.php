<?php
namespace App\Command;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\Trade;
use App\Entity\TradeSide;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTradesFromMfl extends Command
{
    /**
     * @var MFLApi
     */
    private $MFLApi;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var string
     */
    private $mflYear;

    public function __construct(MFLApi $MFLApi, EntityManagerInterface $em, string $mflYear)
    {
        parent::__construct();
        $this->MFLApi = $MFLApi;
        $this->em = $em;
        $this->mflYear = $mflYear;
    }

    public function configure()
    {
        $this
            ->setName("app:synctrades")
            ->setDescription("Sync trade data from MFL")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Hit MFL API endpoint and grab trade data
        $trades = $this->MFLApi->getTrades();

        // For each one
        foreach($trades as $trade) {
            // Skip any trades with no assets on one side - these are commissioner setting draft picks
            if ($trade['franchise1_gave_up'] === '' || $trade['franchise2_gave_up'] === '') {
                continue;
            }

            $tradeHash = md5($trade['timestamp'] . $trade['franchise'] . $trade['franchise1_gave_up'] . $trade['franchise2'] . $trade['franchise2_gave_up']);
            $existingTrade = $this->em->getRepository(Trade::class)->findOneBy([
                'mflApiHash' => $tradeHash,
            ]);
            if ($existingTrade) {
                continue;
            }

            $tradeEntity = new Trade();
            $tradeEntity->setMflApiHash($tradeHash);
            $tradeEntity->setDate(new \DateTime('@' . $trade['timestamp'], new \DateTimeZone('UTC')));
            $this->em->persist($tradeEntity);

            $tradeEntity->addSide($this->tradeSideFromMflData($trade['franchise'], $trade['franchise1_gave_up']));
            $tradeEntity->addSide($this->tradeSideFromMflData($trade['franchise2'], $trade['franchise2_gave_up']));

            foreach ($tradeEntity->getSides() as $side) {
                if (!$tradeEntity->getWinningSide() || ($side->getValue() < $tradeEntity->getWinningSide()->getValue())) {
                    $tradeEntity->setWinningSide($side);
                }
            }
        }

        $this->em->flush();
    }

    private function tradeSideFromMflData(string $franchiseId, string $assetsGivenUp)
    {
        $side = new TradeSide();
        $franchise = $this->em->getRepository(Franchise::class)->findOneBy(['mflFranchiseId' => $franchiseId]);
        // TODO: calculate value side
        $side->setValue(0);
        $side->setFranchise($franchise);
        $this->em->persist($side);

        // Identify assets given up
        foreach(array_filter(explode(',', $assetsGivenUp), function($el) { return mb_strlen($el) > 0; }) as $assetIdentifier) {
            if (substr($assetIdentifier, 0, 3) === 'FP_') {
                $pickData = explode('_', $assetIdentifier);
                /**
                 * 0 = FP
                 * 1 = MFL franchise ID
                 * 2 = year
                 * 3 = round
                 */
                $franchise = $this->em->getRepository(Franchise::class)->findOneBy(['mflFranchiseId' => $pickData[1]]);
                $pick = $this->em->getRepository(DraftPick::class)->getPickByYearRoundOriginalOwnerFranchise($pickData[2], $pickData[3], $franchise);
                $side->addPick($pick);
            } else if (substr($assetIdentifier, 0, 3) === 'DP_') {
                // Current draft pick
                $pickData = explode('_', $assetIdentifier);
                /**
                 * 0 = DP
                 * 1 = round of draft minus 1 (ZERO INDEXED!)
                 * 2 = pick in round minus 1 (ZERO INDEXED!)
                 */
                $pick = $this->em->getRepository(DraftPick::class)->getPickByYearRoundAndPick($this->mflYear, $pickData[1]+1, $pickData[2]+1);
                $side->addPick($pick);
            } else {
                $player = $this->em->getRepository(Player::class)->findOneBy(['externalIdMfl' => $assetIdentifier]);
                $side->addPlayer($player);
            }
        }

        $playersValue = array_reduce($side->getPlayers()->toArray(), function($value, Player $player) {
            return $value + $player->getValue();
        }, 0);

        $picksValue = array_reduce($side->getPicks()->toArray(), function($value, DraftPick $pick) {
            return $value + $pick->getValue();
        }, 0);

        $side->setValue($playersValue + $picksValue);

        return $side;
    }
}
