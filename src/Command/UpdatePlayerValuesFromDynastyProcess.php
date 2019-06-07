<?php
namespace App\Command;

use App\Entity\DraftPick;
use App\Entity\Player;
use App\Entity\Position;
use App\Service\DynastyProcessAPI;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePlayerValuesFromDynastyProcess extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var DynastyProcessAPI
     */
    private $dynastyProcessAPI;

    public function __construct(EntityManagerInterface $em, DynastyProcessAPI $dynastyProcessAPI)
    {
        parent::__construct();
        $this->em = $em;
        $this->dynastyProcessAPI = $dynastyProcessAPI;
    }

    public function configure()
    {
        $this
            ->setName("app:updatevalues")
            ->setDescription("Update player values from DynastyProcess API")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $values = $this->dynastyProcessAPI->getPlayerValues();

        foreach($values as $value) {
            $player = $this->em->getRepository(Player::class)->findOneBy([
                'externalIdMfl' => $value['mflId'],
            ]);
            if (!$player) {
                continue;
            }
            $player->setValue($value['valueQB1']);
        }

        $this->em->flush();

        // Draft picks (only approximate round-based values for now)

        $picks = $this->em->getRepository(DraftPick::class)->getUnusedPicks();
        $pickValues = [];

        /** @var DraftPick $pick */
        foreach ($picks as $pick) {
            $year = $pick->getDraft()->getYear();
            // Has the year already been checked and there's no data?
            if (isset($pickValues[$year]) && $pickValues[$year] === false) {
                continue;
            }
            // Do we have a value for this year yet? If not, go get 'em
            if (!array_key_exists($year, $pickValues)) {
                $values = $this->dynastyProcessAPI->getDraftPickValuesForYear($year);
                if (!$values) {
                    $pickValues[$year] = $values;
                    continue;
                }
                $pickValues[$year] = $values;
            }
            $value = $pickValues[$year][$pick->getRound()];
            $pick->setValue($value);
        }

        $this->em->flush();

    }
}
