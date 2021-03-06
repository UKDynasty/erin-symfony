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

    /**
     * @ORM\Column(type="integer")
     */
    private $gameSecondsRemaining;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

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

    public function getGameSecondsRemaining(): ?int
    {
        return $this->gameSecondsRemaining;
    }

    public function setGameSecondsRemaining(int $gameSecondsRemaining): self
    {
        $this->gameSecondsRemaining = $gameSecondsRemaining;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getGameMinutesRemainingFormatted(): string
    {
        $remaining = $this->getGameSecondsRemaining();

        $minutes = floor($remaining / 60);
        $seconds = $remaining % 60;

        return sprintf(
            '%s:%s',
            str_pad($minutes, 2, '0', STR_PAD_LEFT),
            str_pad($seconds, 2, '0', STR_PAD_LEFT)
        );
    }
}
