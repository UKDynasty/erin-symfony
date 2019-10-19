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

class SyncWeeklyResultsFromMfl extends Command
{
    protected static $defaultName = 'app:sync-weekly-results';

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
            ->setDescription('Sync weekly results for the current week - run once every hour and mark matchups as complete as necessary')
            ->addArgument('week', InputArgument::OPTIONAL, 'week number?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $week = $input->getArgument('week');
        $completed = $this->scheduleManager->syncWeeklyResults($week);

        $io->success('Synced weekly results from MFL - marked ' . $completed  . ' matchups as complete');
    }
}
