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

class UpdateTaxiSquadCounts extends Command
{
    /**
     * @var GoogleSheet
     */
    private $googleSheet;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TaxiSquads
     */
    private $taxiSquads;

    /**
     * UpdateESPNRosterCounts constructor.
     * @param TaxiSquads $taxiSquads
     * @param null|string $name
     */
    public function __construct(TaxiSquads $taxiSquads, ?string $name = null)
    {
        parent::__construct($name);
        $this->taxiSquads = $taxiSquads;
    }

    protected function configure()
    {
        $this
            ->setName('app:updatetaxisquadcounts')
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
        $this->taxiSquads->updateCounts();
    }
}