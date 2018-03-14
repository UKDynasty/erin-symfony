<?php
namespace App\Command;

use App\Entity\Franchise;
use App\Entity\Player;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncFranchiseDataFromMfl extends Command
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
            ->setName("app:syncfranchisedata")
            ->setDescription("Link players to franchises from MFL")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $franchiseRepo = $this->em->getRepository(Franchise::class);
        $playersRepo = $this->em->getRepository(Player::class);

        // Get current rosters from MFL
        $rostersData = $this->MFLApi->getRosters();

        foreach($rostersData as $rosterData) {
            /** @var Franchise $franchise */
            $franchise = $franchiseRepo->findOneBy([
                "mflFranchiseId" => $rosterData["id"],
            ]);
            // Unlink all players
            foreach($franchise->getPlayers() as $player) {
                $player->setFranchise(null);
                $this->em->persist($player);
            }
            // Link all current players
            foreach($rosterData["player"] as $rosteredPlayerData) {
                /** @var Player $player */
                $player = $playersRepo->findOneBy(["externalIdMfl" => $rosteredPlayerData["id"]]);
                if (!$player) {
                    dump($rosteredPlayerData);
                    continue;
                }
                $player->setFranchise($franchise);
            }
        }

        $this->em->flush();
    }
}