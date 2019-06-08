<?php
namespace App\Command;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\Trade;
use App\Entity\TradeSide;
use App\Entity\TradeSideDraftPick;
use App\Entity\TradeSidePlayer;
use App\GroupMe\GroupMessage;
use App\Service\Erin;
use App\Service\GroupMe;
use App\Service\HumanReadableHelpers;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\UnexpectedValueException;
use mysql_xdevapi\Exception;
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

    /**
     * @var GroupMe
     */
    private $groupMe;
    /**
     * @var HumanReadableHelpers
     */
    private $helpers;

    public function __construct(MFLApi $MFLApi, EntityManagerInterface $em, string $mflYear, GroupMe $groupMe, HumanReadableHelpers $helpers)
    {
        parent::__construct();
        $this->MFLApi = $MFLApi;
        $this->em = $em;
        $this->mflYear = $mflYear;
        $this->groupMe = $groupMe;
        $this->helpers = $helpers;
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

            $tradeEntity->setValueDifference($this->calculateValueDifference($tradeEntity));

            // TODO: move to event listener
            $message[] = '🌭🌭🌭 TRADE ALERT 🌭🌭🌭';
            $message[] = $this->helpers->tradeToText($tradeEntity);

            if ($tradeEntity->getValueDifference() < 5) {
                $analysis = "A good trade for both teams. I can't pick a winner of that one.";
            } else if ($tradeEntity->getValueDifference() < 15) {
                $analysis = "Nice trade! That's a tough one to call, but I think the %s won that trade by a sliver.";
            } else if ($tradeEntity->getValueDifference() <= 50) {
                $analysis = 'I love to see teams making moves! I really like this one for the %s.';
            } else {
                $analysis = "Wow! I'm a big fan of that trade for the %s.";
            }

            $message[] = sprintf($analysis, $tradeEntity->getWinningSide()->getFranchise()->getName());

            $message[] = sprintf('Difference is %s', $tradeEntity->getValueDifference());

            $groupMeMessage = new GroupMessage();
            $groupMeMessage->setText(implode("\n\n", $message));
            $this->groupMe->sendGroupMessage($groupMeMessage);
        }


        $this->em->flush();
    }

    private function calculateValueDifference(Trade $trade): float
    {
        $lowestValue = $trade->getWinningSide()->getValue() > 0 ? $trade->getWinningSide()->getValue() : 1;

        foreach($trade->getSides() as $tradeSide) {
            if ($trade->getWinningSide() !== $tradeSide) {
                $highestValue = $tradeSide->getValue() > 0 ? $tradeSide->getValue() : 1;
            }
        }

        if (!isset($lowestValue)) {
            throw new \Exception('No lowest value found, something is wrong here');
        }

        return (($highestValue - $lowestValue) / $lowestValue) * 100;
    }

    private function tradeSideFromMflData(string $franchiseId, string $assetsGivenUp): TradeSide
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
                $side->addPick(new TradeSideDraftPick($side, $pick, $pick->getValue() ?? 0));
            } else if (substr($assetIdentifier, 0, 3) === 'DP_') {
                // Current draft pick
                $pickData = explode('_', $assetIdentifier);
                /**
                 * 0 = DP
                 * 1 = round of draft minus 1 (ZERO INDEXED!)
                 * 2 = pick in round minus 1 (ZERO INDEXED!)
                 */
                $pick = $this->em->getRepository(DraftPick::class)->getPickByYearRoundAndPick($this->mflYear, $pickData[1]+1, $pickData[2]+1);
                $side->addPick(new TradeSideDraftPick($side, $pick, $pick->getValue() ?? 0));
            } else {
                $player = $this->em->getRepository(Player::class)->findOneBy(['externalIdMfl' => $assetIdentifier]);
                $side->addPlayer(new TradeSidePlayer($side, $player, $player->getValue() ?? 0));
            }
        }

        $playersValue = array_reduce($side->getPlayers()->toArray(), function($value, TradeSidePlayer $player) {
            return $value + $player->getPlayer()->getValue();
        }, 0);

        $picksValue = array_reduce($side->getPicks()->toArray(), function($value, TradeSideDraftPick $pick) {
            return $value + $pick->getPick()->getValue();
        }, 0);

        $side->setValue($playersValue + $picksValue);

        return $side;
    }
}
