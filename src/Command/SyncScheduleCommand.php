<?php

namespace App\Command;

use App\Service\MFLApi;
use App\Service\ScheduleManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncScheduleCommand extends Command
{
    protected static $defaultName = 'app:sync-schedule';

    /**
     * @var ScheduleManager
     */
    private $scheduleManager;

    public function __construct(string $name = NULL, ScheduleManager $scheduleManager)
    {
        parent::__construct($name);

        $this->scheduleManager = $scheduleManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Sync regular season schedule for current season - create Matchup and MatchupFranchise for all matchups, and Season/Weeks if not existing. Warning: this should be done once per season. Matchups will be re-created even if they already exist.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->scheduleManager->syncSchedule();

        $io->success('Synced schedule from MFL');
    }
}
