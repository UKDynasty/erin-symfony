<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\PlayerRepository;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncAssetsDataFromMfl extends Command
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
            ->setName("app:syncassetsdata")
            ->setDescription("Sync MFL's trade bait for each team")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $franchiseRepo = $this->em->getRepository(Franchise::class);
        /** @var PlayerRepository $playersRepo */
        $playersRepo = $this->em->getRepository(Player::class);
        $draftRepo = $this->em->getRepository(Draft::class);

        // Remove current trade bait
        $currentTradeBait = $playersRepo->findAll();
        foreach($currentTradeBait as $player) {
            /** @var Player $player */
            $player->setListedAsTradeBait(false);
        }

        // Get assets data from MFL
        $assetsData = $this->MFLApi->getAssets();

        foreach($assetsData as $assetsDatum) {
            // $assetsDatum["futureYearDraftPicks"]["draftPick"], ["currentYearDraftPicks"]["draftPick"], ["players"]["draftPick"]

            /** @var Franchise $franchise */
            $franchise = $franchiseRepo->findOneBy([
                "mflFranchiseId" => $assetsDatum["id"],
            ]);


            if (isset($assetsDatum["futureYearDraftPicks"]["draftPick"])) {
                $futureYearDraftPicks = isset($assetsDatum["futureYearDraftPicks"]["draftPick"][0]) ? $assetsDatum["futureYearDraftPicks"]["draftPick"] : [$assetsDatum["futureYearDraftPicks"]["draftPick"]];

                foreach($futureYearDraftPicks as $futureYearDraftPick) {
                    $mflIdentifier = $futureYearDraftPick["pick"];
                    $parts = explode("_", $mflIdentifier);
                    $franchise = $franchiseRepo->findOneBy([
                        "mflFranchiseId" => $parts[1],
                    ]);
                    $draft = $draftRepo->findOneBy([
                        "year" => $parts[2],
                    ]);
                    /** @var DraftPick $pick */
                    $pick = $this->em->getRepository(DraftPick::class)->findOneBy([
                        "originalOwner" => $franchise,
                        "draft" => $draft,
                        "round" => $parts[3],
                    ]);
                    $pick->setOwner($franchise);
                }
            }

            if (isset($assetsDatum["currentYearDraftPicks"]["draftPick"])) {
                $currentYearDraftPicks = isset($assetsDatum["currentYearDraftPicks"]["draftPick"][0]) ? $assetsDatum["currentYearDraftPicks"]["draftPick"] : [$assetsDatum["currentYearDraftPicks"]["draftPick"]];

                foreach($currentYearDraftPicks as $currentYearDraftPick) {
                    $mflIdentifier = $currentYearDraftPick["pick"];
                    $parts = explode("_", $mflIdentifier);
                    $round = $parts[1] + 1;
                    $number = $parts[2] + 1;
                    $year = explode(" ", $currentYearDraftPick["description"])[1];
                    $draft = $draftRepo->findOneBy([
                        "year" => $year,
                    ]);
                    $pick = $this->em->getRepository(DraftPick::class)->findOneBy([
                        "draft" => $draft,
                        "round" => $round,
                        "number" => $number,
                    ]);
                    if ($round === 1 && $number === 1) {
                        dump($currentYearDraftPicks);
                    }
                    $pick->setOwner($franchise);
                }
            }
        }

        $this->em->flush();
    }
}