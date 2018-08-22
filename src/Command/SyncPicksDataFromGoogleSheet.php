<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\Entity\PlayerRepository;
use App\Service\FuturePicks;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncPicksDataFromGoogleSheet extends Command
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
     * @var FuturePicks
     */
    private $futurePicks;

    public function __construct(FuturePicks $futurePicks, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->futurePicks = $futurePicks;
    }

    public function configure()
    {
        $this
            ->setName("app:syncpicksdata")
            ->setDescription("Sync picks from Google Sheet (during season when on ESPN)")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->futurePicks->updatePicks();
    }
}