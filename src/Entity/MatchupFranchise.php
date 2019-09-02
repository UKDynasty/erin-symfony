<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchupFranchiseRepository")
 */
class MatchupFranchise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matchup", inversedBy="matchupFranchises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matchup;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $score = 0;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Franchise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $franchise;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MatchupPlayer", mappedBy="matchupFranchise", orphanRemoval=true)
     */
    private $matchupPlayers;

    /**
     * @ORM\Column(type="boolean")
     */
    private $home;

    public function __construct(Matchup $matchup, Franchise $franchise, bool $home)
    {
        $this->matchupPlayers = new ArrayCollection();
        $this->matchup = $matchup;
        $this->home = $home;
        $this->franchise = $franchise;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchup(): ?Matchup
    {
        return $this->matchup;
    }

    public function setMatchup(?Matchup $matchup): self
    {
        $this->matchup = $matchup;

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

    public function getWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(?bool $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(?Franchise $franchise): self
    {
        $this->franchise = $franchise;

        return $this;
    }

    /**
     * @return Collection|MatchupPlayer[]
     */
    public function getMatchupPlayers(): Collection
    {
        return $this->matchupPlayers;
    }

    public function addMatchupPlayer(MatchupPlayer $matchupPlayer): self
    {
        if (!$this->matchupPlayers->contains($matchupPlayer)) {
            $this->matchupPlayers[] = $matchupPlayer;
            $matchupPlayer->setMatchupFranchise($this);
        }

        return $this;
    }

    public function removeMatchupPlayer(MatchupPlayer $matchupPlayer): self
    {
        if ($this->matchupPlayers->contains($matchupPlayer)) {
            $this->matchupPlayers->removeElement($matchupPlayer);
            // set the owning side to null (unless already changed)
            if ($matchupPlayer->getMatchupFranchise() === $this) {
                $matchupPlayer->setMatchupFranchise(null);
            }
        }

        return $this;
    }

    public function getHome(): ?bool
    {
        return $this->home;
    }

    public function setHome(bool $home): self
    {
        $this->home = $home;

        return $this;
    }
}
