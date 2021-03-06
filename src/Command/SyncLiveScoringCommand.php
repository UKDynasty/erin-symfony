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

class SyncLiveScoringCommand extends Command
{
    protected static $defaultName = 'app:sync-live-scoring';

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
            ->setDescription('Sync live-scoring for the current week')
            ->addArgument('week', InputArgument::OPTIONAL, 'week number?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $week = $input->getArgument('week');
        $matchups = $this->scheduleManager->syncLiveScoring($week);

        foreach($matchups as $matchup) {
            $output->writeln($matchup->toStringForErin());
        }

        $io->success('Synced live-scoring from MFL');
    }
}
