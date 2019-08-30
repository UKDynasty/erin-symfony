<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeekRepository")
 */
class Week
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Season", inversedBy="weeks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Matchup", mappedBy="week", orphanRemoval=true)
     */
    private $matchups;

    public function __construct(Season $season, int $number)
    {
        $this->setSeason($season);
        $this->setNumber($number);
        $this->matchups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Matchup[]
     */
    public function getMatchups(): Collection
    {
        return $this->matchups;
    }

    public function addMatchup(Matchup $matchup): self
    {
        if (!$this->matchups->contains($matchup)) {
            $this->matchups[] = $matchup;
            $matchup->setWeek($this);
        }

        return $this;
    }

    public function removeMatchup(Matchup $matchup): self
    {
        if ($this->matchups->contains($matchup)) {
            $this->matchups->removeElement($matchup);
            // set the owning side to null (unless already changed)
            if ($matchup->getWeek() === $this) {
                $matchup->setWeek(null);
            }
        }

        return $this;
    }
}
