<?php

namespace App\Command;

use App\Entity\MatchupPlayer;
use App\Repository\MatchupRepository;
use App\Service\ScheduleManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckMatchupsCompleteCommand extends Command
{
    protected static $defaultName = 'app:check-matchups-complete';

    /**
     * @var ScheduleManager
     */
    private $scheduleManager;
    /**
     * @var MatchupRepository
     */
    private $matchupRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(string $name = NULL, ScheduleManager $scheduleManager, MatchupRepository $matchupRepository, EntityManagerInterface $em)
    {
        parent::__construct($name);

        $this->scheduleManager = $scheduleManager;
        $this->matchupRepository = $matchupRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Check to see if the current week is complete')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $week = $this->scheduleManager->getCurrentWeek();
        $matchups = $this->matchupRepository->findByWeek($week);

        foreach($matchups as $matchup) {
            $playersLeftToPlay = 0;
            foreach($matchup->getMatchupFranchises() as $matchupFranchise) {
                $playersLeftToPlay += (9 - $matchupFranchise->getMatchupPlayers()->filter(function(MatchupPlayer $matchupPlayer) {
                    return $matchupPlayer->getGameSecondsRemaining() > 0;
                }));
            }

            if ($playersLeftToPlay === 0) {
                $matchup->setComplete(true);
                $matchup->calculateWinner();
            }
        }

        $this->em->flush();

        $io->success('Checked for complete matchups in week ' . $week->getNumber());
    }
}
