<?php
namespace App\Service;

use App\Entity\Franchise;
use App\Entity\Matchup;
use App\Entity\MatchupFranchise;
use App\Entity\MatchupPlayer;
use App\Entity\Player;
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

    /**
     * @return array|Matchup[]
     */
    public function syncLiveScoring(int $week = null): array
    {
        $liveScoring = $this->MFLApi->getLiveScoring($week);

        $season = $this->em->getRepository(Season::class)->findOneBy(['year' => $this->mflYear]);
        $week = $this->em->getRepository(Week::class)->findOneBy([
            'number' => $liveScoring['week'],
            'season' => $season,
        ]);

        if (!$week) {
            // In case CRON is being run without schedule being updated yet (when season is just created)
            return [];
        }

        $matchups = [];

        foreach($liveScoring['matchup'] as $liveScoringMatchup) {
            // Identify the matchup from the franchises involved. If we ever have double-headers, this won't work
            $franchiseRepo = $this->em->getRepository(Franchise::class);
            $matchupRepo = $this->em->getRepository(Matchup::class);
            $matchupFranchiseRepo = $this->em->getRepository(MatchupFranchise::class);
            $matchupPlayerRepo = $this->em->getRepository(MatchupPlayer::class);

            $firstFranchise = $franchiseRepo->findOneBy([
                'mflFranchiseId' => $liveScoringMatchup['franchise'][0]['id'],
            ]);
            /** @var Matchup $matchup */
            $matchup = $matchupRepo->findOneByWeekForFranchise($week, $firstFranchise);

            foreach($liveScoringMatchup['franchise'] as $liveScoringMatchupFranchise) {
                // Identify MatchupFranchise
                $franchise = $franchiseRepo->findOneBy([
                    'mflFranchiseId' => $liveScoringMatchupFranchise['id'],
                ]);

                /** @var MatchupFranchise $matchupFranchise */
                $matchupFranchise = $matchupFranchiseRepo->findOneBy([
                    'matchup' => $matchup,
                    'franchise' => $franchise,
                ]);

                $matchupFranchise->setScore($liveScoringMatchupFranchise['score']);

                /**
                 * Sync all the players
                 */
                $ids = [];
                foreach($liveScoringMatchupFranchise['players']['player'] as $liveScoringMatchupPlayer) {
                    if ($liveScoringMatchupPlayer['status'] !== 'starter') {
                        // Skip bench players for now
                        continue;
                    }

                    $ids[] = $liveScoringMatchupPlayer['id'];

                    $matchupPlayer = $matchupPlayerRepo->findByMflPlayerIdAndMatchupFranchise($liveScoringMatchupPlayer['id'], $matchupFranchise);
                    if (!$matchupPlayer) {
                        $matchupPlayer = new MatchupPlayer();
                        $matchupPlayer->setMatchupFranchise($matchupFranchise);
                        $matchupPlayer->setPlayer($this->em->getRepository(Player::class)->findOneBy(['externalIdMfl' => $liveScoringMatchupPlayer['id']]));
                        $this->em->persist($matchupPlayer);
                    }

                    $matchupPlayer->setGameSecondsRemaining($liveScoringMatchupPlayer['gameSecondsRemaining']);
                    $matchupPlayer->setScore($liveScoringMatchupPlayer['score']);
                }

                // Find any matchupPlayers not in the updated IDs and delete them (they're players who were in lineups but swapped out)
                $swappedOutPlayers = $matchupFranchise->getMatchupPlayers()->filter(function(MatchupPlayer $matchupPlayer) use ($ids) {
                    return !in_array($matchupPlayer->getPlayer()->getExternalIdMfl(), $ids);
                });
                foreach($swappedOutPlayers as $swappedOutPlayer) {
                    $this->em->remove($swappedOutPlayer);
                }
            }

            /**
             * Set as complete if it's complete
             */
            $playersPlayed = 0;
            foreach($matchup->getMatchupFranchises() as $matchupFranchise) {
                foreach($matchupFranchise->getMatchupPlayers() as $matchupPlayer) {
                    if ($matchupPlayer->getGameSecondsRemaining() === 0) {
                        $playersPlayed++;
                    }
                }
            }

            $matchups[] = $matchup;
        }

        $this->em->flush();

        return $matchups;

    }

    public function syncWeeklyResults(int $week = null): int
    {
        $weeklyResults = $this->MFLApi->getWeeklyResults($week);

        $season = $this->em->getRepository(Season::class)->findOneBy(['year' => $this->mflYear]);
        $week = $this->em->getRepository(Week::class)->findOneBy([
            'number' => $weeklyResults['week'],
            'season' => $season,
        ]);

        if (!$week) {
            // In case CRON is being run without schedule being updated yet (when season is just created)
            return 0;
        }

        $franchiseRepo = $this->em->getRepository(Franchise::class);
        $matchupRepo = $this->em->getRepository(Matchup::class);

        $completed = 0;

        foreach($weeklyResults['matchup'] as $matchupData) {
            $firstFranchise = $franchiseRepo->findOneBy([
                'mflFranchiseId' => $matchupData['franchise'][0]['id'],
            ]);
            /** @var Matchup $matchup */
            $matchup = $matchupRepo->findOneByWeekForFranchise($week, $firstFranchise);

            foreach($matchupData['franchise'] as $franchiseData) {
                if (isset($franchiseData['result'])) {
                    $completed++;
                    $matchup->setComplete(true);
                    $matchup->calculateWinner();
                    continue 2;
                }
            }
        }

        $this->em->flush();

        return $completed;

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
