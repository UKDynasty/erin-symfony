<?php
namespace App\Command;

use App\Entity\Player;
use App\Entity\Position;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePlayersFromMfl extends Command
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
            ->setName("app:updateplayers")
            ->setDescription("Update players from MFL")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $players = array_values(array_filter(
            $this->MFLApi->getPlayers(),
            function($element) {
                // Remove "TM" positions like "team wide receivers" - we don't do these
                return !preg_match("/^(PK|PN|DT|DE|Def|LB|DB|CB|S|TM|ST|Off|Coach)/", $element["position"]);
            }
        ));
//        $players = $this->MFLApi->getPlayers();
        $total = count($players);
        foreach($players as $index => $player) {
            $output->writeln("Processing player " . ($index+1) . " of " . $total);
            $this->updatePlayer($player, $input, $output);
        }
        $this->em->flush();
    }

    private function updatePlayer($mflPlayer, InputInterface $input, OutputInterface $output)
    {
        $playerRepository = $this->em->getRepository(Player::class);
        $player = $playerRepository->findOneBy(["externalIdMfl" => $mflPlayer["id"]]);
        $positionRepository = $this->em->getRepository(Position::class);
        $position = $positionRepository->findOneBy([
            "name" => $mflPlayer["position"],
        ]);

        if (!$player) {
            $player = new Player();
            $player->setExternalIdMfl($mflPlayer["id"]);
            $this->em->persist($player);
        }
        $player->setPosition($position);
        $nameArray = explode(", ", $mflPlayer["name"]);
        $player->setFirstName($nameArray[1]);
        $player->setLastName($nameArray[0]);
        $player->setExternalIdMfl($mflPlayer["id"]);
        $player->setExternalIdEspn($mflPlayer["espn_id"] ?? null);
        $player->setExternalIdRotoworld($mflPlayer["rotoworld_id"] ?? null);
        $player->setExternalIdStats($mflPlayer["stats_id"] ?? null);
        $player->setExternalIdStatsGlobal($mflPlayer["stats_global_id"] ?? null);
        $player->setExternalIdFleaflicker($mflPlayer["fleaflicker_id"] ?? null);
        $player->setExternalIdKffl($mflPlayer["kffl_id"] ?? null);
        $player->setExternalIdSportsdata($mflPlayer["sportsdata_id"] ?? null);
        $player->setExternalIdCbs($mflPlayer["cbs_id"] ?? null);
        $player->setTwitterHandle($mflPlayer["twitter_username"] ?? null);
        $player->setCollege($mflPlayer["college"] ?? null);
        $player->setDraftTeam($mflPlayer["draft_team"] ??  null);
        $player->setDraftYear($mflPlayer["draft_year"] ?? null);
        $player->setDraftRound($mflPlayer["draft_round"] ?? null);
        $player->setDraftPick($mflPlayer["draft_pick"] ?? null);
        $player->setWeight($mflPlayer["weight"] ?? null);
        $player->setHeight($mflPlayer["height"] ?? null);
        $player->setJersey($mflPlayer["jersey"] ?? null);
        $player->setBirthdate(isset($mflPlayer["birthdate"]) ? new \DateTime("@".$mflPlayer["birthdate"]) : null);
    }
}