<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\GroupMe\GroupMessage;
use App\Service\ESPNRosterManager;
use App\Service\GoogleSheet;
use App\Service\GroupMe;
use App\Service\HumanReadableHelpers;
use App\Service\MFLApi;
use App\Service\TaxiSquads;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckESPNRosterLimits extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ESPNRosterManager
     */
    private $ESPNRosterManager;
    /**
     * @var TaxiSquads
     */
    private $taxiSquads;
    /**
     * @var GroupMe
     */
    private $groupMe;

    /**
     * UpdateESPNRosterCounts constructor.
     * @param EntityManagerInterface $entityManager
     * @param ESPNRosterManager $ESPNRosterManager
     * @param TaxiSquads $taxiSquads
     * @param GroupMe $groupMe
     * @param null|string $name
     */
    public function __construct(EntityManagerInterface $entityManager, ESPNRosterManager $ESPNRosterManager, TaxiSquads $taxiSquads, GroupMe $groupMe,  ?string $name = null)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->ESPNRosterManager = $ESPNRosterManager;
        $this->taxiSquads = $taxiSquads;
        $this->groupMe = $groupMe;
    }

    protected function configure()
    {
        $this
            ->setName('app:checkespnrosterlimits')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->ESPNRosterManager->updateRosterCounts();
        $this->taxiSquads->updateCounts();

        $franchises = $this->entityManager->getRepository(Franchise::class)->findAll();
        foreach($franchises as $franchise) {
            $properCount = $franchise->getEspnRosterCountRegular() - $franchise->getTaxiSquadCount();
            if ($properCount > 25) {
                $message = sprintf(
                    'Scandalous! The %s are over the 25-man roster limit. They have %s players on the regular roster, and only %s of them are designated to their taxi squad. Sort it out %s!',
                    $franchise->getName(),
                    $franchise->getEspnRosterCountRegular(),
                    $franchise->getTaxiSquadCount(),
                    $franchise->getOwner()->getName()
                );
                $groupMeMessage = new GroupMessage();
                $groupMeMessage->setText($message);
                $this->groupMe->sendGroupMessage($groupMeMessage);
            }
        }
    }
}