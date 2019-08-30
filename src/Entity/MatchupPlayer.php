<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchupPlayerRepository")
 */
class MatchupPlayer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MatchupFranchise", inversedBy="matchupPlayers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchupFranchise;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchupFranchise(): ?MatchupFranchise
    {
        return $this->matchupFranchise;
    }

    public function setMatchupFranchise(?MatchupFranchise $matchupFranchise): self
    {
        $this->matchupFranchise = $matchupFranchise;

        return $this;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($score): self
    {
        $this->score = $score;

        return $this;
    }
}
