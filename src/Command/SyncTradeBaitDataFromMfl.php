<?php
namespace App\Command;

use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\PlayerRepository;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncTradeBaitDataFromMfl extends Command
{
    /**
     * @var MFLApi
     */
    private $MFLApi;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(MFLApi $MFLApi, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->MFLApi = $MFLApi;
        $this->em = $em;
    }

    public function configure()
    {
        $this
            ->setName("app:synctradebaitdata")
            ->setDescription("Sync MFL's trade bait for each team")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $franchiseRepo = $this->em->getRepository(Franchise::class);
        /** @var PlayerRepository $playersRepo */
        $playersRepo = $this->em->getRepository(Player::class);

        // Remove current trade bait
        $currentTradeBait = $playersRepo->findAll();
        foreach($currentTradeBait as $player) {
            /** @var Player $player */
            $player->setListedAsTradeBait(false);
        }

        // Get current rosters from MFL
        $rostersData = $this->MFLApi->getTradeBait();

        foreach($rostersData as $rosterData) {
            /** @var Franchise $franchise */
            $franchise = $franchiseRepo->findOneBy([
                "mflFranchiseId" => $rosterData["franchise_id"],
            ]);
            // Set current trade bait
            $playerIds = explode(",", $rosterData["willGiveUp"]);
            foreach($playerIds as $playerId) {
                /** @var Player $player */
                $player = $playersRepo->findOneBy(["externalIdMfl" => $playerId]);
                if (!$player) {
                    // This could be a player that doesn't exist in our DB, but is more likely to be a draft pick. Could check for draft picks here.
                    dump($playerId);
                    continue;
                }
                $player->setListedAsTradeBait(true);
            }
        }

        $this->em->flush();
    }
}