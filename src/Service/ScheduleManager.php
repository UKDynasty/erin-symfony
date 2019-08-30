<?php
namespace App\Service;

use App\Entity\Franchise;
use App\Entity\Matchup;
use App\Entity\MatchupFranchise;
use App\Entity\Season;
use App\Entity\Week;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleManager
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
     * @var int
     */
    private $mflYear;

    public function __construct(EntityManagerInterface $em, MFLApi $MFLApi, int $mflYear)
    {
        $this->MFLApi = $MFLApi;
        $this->em = $em;
        $this->mflYear = $mflYear;
    }

    public function syncSchedule()
    {
        $schedule = $this->MFLApi->getSchedule();

        $season = $this->em->getRepository(Season::class)->findOneBy(['year' => $this->mflYear]);

        if (!$season) {
            $season = new Season($this->mflYear);
            $this->em->persist($season);
        }

        foreach($schedule as $scheduleWeek) {
            if (!isset($scheduleWeek['matchup'])) {
                // No matchups for this week (probably a playoff week)
                continue;
            }

            $week = $this->em->getRepository(Week::class)->findOneBy(['number' => $scheduleWeek['week']]);

            if (!$week) {
                $week = new Week($season, $scheduleWeek['week']);
                $this->em->persist($week);
            }

            foreach($scheduleWeek['matchup'] as $scheduleMatchup) {
                $matchup = new Matchup($week);
                $this->em->persist($matchup);

                foreach($scheduleMatchup['franchise'] as $scheduleMatchupFranchise) {
                    $franchise = $this->em->getRepository(Franchise::class)->findOneBy(['mflFranchiseId' => $scheduleMatchupFranchise['id']]);
                    $matchupFranchise = new MatchupFranchise($matchup, $franchise, $scheduleMatchupFranchise['isHome'] === '1');
                    $this->em->persist($matchupFranchise);
                }

            }
        }

        $this->em->flush();
    }

    public function getCurrentSeason(): Season
    {
        return $this->em->getRepository(Season::class)->findOneBy(['year' => $this->mflYear]);
    }

    public function getCurrentWeek(): ?Week
    {
        $dql = $this->em->createQuery('SELECT w FROM App\Entity\Week w INNER JOIN w.matchups m WHERE m.complete = false ORDER BY w.number ASC');
        return $dql->getResult()[0] ?? null;
    }
}
