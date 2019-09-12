<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchupRepository")
 */
class Matchup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Week", inversedBy="matchups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $week;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MatchupFranchise", mappedBy="matchup", orphanRemoval=true)
     * @ORM\OrderBy({"home": "ASC"})
     */
    private $matchupFranchises;

    /**
     * @ORM\Column(type="boolean")
     */
    private $complete = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $regularSeason = true;

    public function __construct(Week $week)
    {
        $this->week = $week;
        $this->matchupFranchises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): self
    {
        $this->week = $week;

        return $this;
    }

    /**
     * @return Collection|MatchupFranchise[]
     */
    public function getMatchupFranchises(): Collection
    {
        return $this->matchupFranchises;
    }

    public function addMatchupFranchise(MatchupFranchise $matchupFranchise): self
    {
        if (!$this->matchupFranchises->contains($matchupFranchise)) {
            $this->matchupFranchises[] = $matchupFranchise;
            $matchupFranchise->setMatchup($this);
        }

        return $this;
    }

    public function removeMatchupFranchise(MatchupFranchise $matchupFranchise): self
    {
        if ($this->matchupFranchises->contains($matchupFranchise)) {
            $this->matchupFranchises->removeElement($matchupFranchise);
            // set the owning side to null (unless already changed)
            if ($matchupFranchise->getMatchup() === $this) {
                $matchupFranchise->setMatchup(null);
            }
        }

        return $this;
    }

    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): self
    {
        $this->complete = $complete;

        return $this;
    }

    public function getRegularSeason(): ?bool
    {
        return $this->regularSeason;
    }

    public function setRegularSeason(bool $regularSeason): self
    {
        $this->regularSeason = $regularSeason;

        return $this;
    }

    /**
     * If the score of both franchises is 0, then the matchup is scoreless.
     * We use this to vaguely work out that the matchup hasn't started yet
     * @return bool
     */
    public function isScoreless()
    {
        return $this->getMatchupFranchises()->filter(function(MatchupFranchise $matchupFranchise) {
            return $matchupFranchise->getScore() === "0.00";
        })->count() === 2;
    }

    public function toStringForErin(): string
    {
        if ($this->isScoreless()) {
            return implode("\n @ ", $this->getMatchupFranchises()->map(function(MatchupFranchise $matchupFranchise) {
                return $matchupFranchise->getFranchise()->getName();
            })->toArray());
        } else {
            return implode("\n", $this->getMatchupFranchises()->map(function(MatchupFranchise $matchupFranchise) {
                return sprintf('%s %s', $matchupFranchise->getFranchise()->getName(), $matchupFranchise->getScore());
            })->toArray());
        }
    }

    public function toStringForAlexa()
    {
        $homeTeam = $this->getMatchupFranchises()->first()->getFranchise();
        $awayTeam = $this->getMatchupFranchises()->last()->getFranchise();

        if ($this->isScoreless()) {
            $messages = [
                sprintf('The %s will host the %s', $homeTeam, $awayTeam),
                sprintf('%s will visit the %s', $awayTeam, $homeTeam),
                sprintf('%s face a trip to play the %s', $awayTeam, $homeTeam),
                sprintf('%s welcome the %s', $homeTeam, $awayTeam),
                sprintf('%s play the %s at home', $homeTeam, $awayTeam),
                sprintf('%s travel to the %s', $awayTeam, $homeTeam),
            ];
            return $messages[array_rand($messages)];
        } else {
            $sortedTeams = $this->getMatchupFranchiseSortedByPoints();
            /** @var MatchupFranchise $winningTeam */
            $winningTeam = $sortedTeams->first();
            /** @var MatchupFranchise $losingTeam */
            $losingTeam = $sortedTeams->last();

            if ($this->complete) {
                $messages = [
                    sprintf('The %s beat the %s by %s points to %s', $winningTeam->getFranchise(), $losingTeam->getFranchise(), $winningTeam->getScore(), $losingTeam->getScore()),
                ];
            } else {
                $messages = [
                    sprintf('The %s are beating the %s by %s points to %s', $winningTeam->getFranchise(), $losingTeam->getFranchise(), $winningTeam->getScore(), $losingTeam->getScore()),
                    sprintf('The %s lead the %s by %s points to %s',  $winningTeam->getFranchise(), $losingTeam->getFranchise(), $winningTeam->getScore(), $losingTeam->getScore()),
                ];
            }

            return $messages[array_rand($messages)];
        }
    }

    private function getMatchupFranchiseSortedByPoints()
    {
        $criteria = Criteria::create()->orderBy(['score' => 'DESC']);
        return $this->matchupFranchises->matching($criteria);
    }

    public function calculateWinner()
    {
        $sortedTeams = $this->getMatchupFranchiseSortedByPoints();
        /** @var MatchupFranchise $winningTeam */
        $winningTeam = $sortedTeams->first();
        $losingTeam = $sortedTeams->last();

        $winningTeam->setWinner(true);
        $losingTeam->setWinner(false);
    }
}
