<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\GroupMe\GroupMessage;
use App\Service\ESPNRosterManager;
use App\Service\GroupMe;
use App\Service\HumanReadableHelpers;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateESPNRosterCounts extends Command
{
    /**
     * @var ESPNRosterManager
     */
    private $ESPNRosterManager;

    /**
     * UpdateESPNRosterCounts constructor.
     * @param null|string $name
     * @param ESPNRosterManager $ESPNRosterManager
     */
    public function __construct(ESPNRosterManager $ESPNRosterManager, ?string $name = null)
    {
        parent::__construct($name);
        $this->ESPNRosterManager = $ESPNRosterManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:updateespnrostercounts')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->ESPNRosterManager->updateRosterCounts();
    }
}