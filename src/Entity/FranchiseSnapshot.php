<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FranchiseSnapshotRepository")
 */
class FranchiseSnapshot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Franchise", inversedBy="snapshots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $franchise;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\FranchiseSnapshotRosterPlayer", mappedBy="snapshot", orphanRemoval=true, cascade={"PERSIST"})
     */
    private $roster;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FranchiseSnapshotBestLineupPlayer", mappedBy="snapshot", orphanRemoval=true, cascade={"PERSIST"})
     */
    private $bestLineup;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $rosterValue;

    /**
     * @ORM\Column(type="float")
     */
    private $rosterValueAverage;

    /**
     * @ORM\Column(type="integer")
     */
    private $rosterCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $bestLineupValue;

    /**
     * @ORM\Column(type="float")
     */
    private $bestLineupValueAverage;

    public function __construct()
    {
        $this->roster = new ArrayCollection();
        $this->bestLineup = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|FranchiseSnapshotRosterPlayer[]
     */
    public function getRoster(): Collection
    {
        return $this->roster;
    }

    public function addToRoster(FranchiseSnapshotRosterPlayer $roster): self
    {
        if (!$this->roster->contains($roster)) {
            $this->roster[] = $roster;
            $roster->setSnapshot($this);
        }

        return $this;
    }

    public function removeFromRoster(FranchiseSnapshotRosterPlayer $roster): self
    {
        if ($this->roster->contains($roster)) {
            $this->roster->removeElement($roster);
            // set the owning side to null (unless already changed)
            if ($roster->getSnapshot() === $this) {
                $roster->setSnapshot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FranchiseSnapshotBestLineupPlayer[]
     */
    public function getBestLineup(): Collection
    {
        return $this->bestLineup;
    }

    public function addToBestLineup(FranchiseSnapshotBestLineupPlayer $bestLineup): self
    {
        if (!$this->bestLineup->contains($bestLineup)) {
            $this->bestLineup[] = $bestLineup;
            $bestLineup->setSnapshot($this);
        }

        return $this;
    }

    public function removeFromBestLineup(FranchiseSnapshotBestLineupPlayer $bestLineup): self
    {
        if ($this->bestLineup->contains($bestLineup)) {
            $this->bestLineup->removeElement($bestLineup);
            // set the owning side to null (unless already changed)
            if ($bestLineup->getSnapshot() === $this) {
                $bestLineup->setSnapshot(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRosterValue(): ?int
    {
        return $this->rosterValue;
    }

    public function setRosterValue(int $rosterValue): self
    {
        $this->rosterValue = $rosterValue;

        return $this;
    }

    public function getRosterValueAverage(): ?float
    {
        return $this->rosterValueAverage;
    }

    public function setRosterValueAverage(float $rosterValueAverage): self
    {
        $this->rosterValueAverage = $rosterValueAverage;

        return $this;
    }

    public function getRosterCount(): ?int
    {
        return $this->rosterCount;
    }

    public function setRosterCount(int $rosterCount): self
    {
        $this->rosterCount = $rosterCount;

        return $this;
    }

    public function getBestLineupValue(): ?int
    {
        return $this->bestLineupValue;
    }

    public function setBestLineupValue(int $bestLineupValue): self
    {
        $this->bestLineupValue = $bestLineupValue;

        return $this;
    }

    public function getBestLineupValueAverage(): ?float
    {
        return $this->bestLineupValueAverage;
    }

    public function setBestLineupValueAverage(float $bestLineupValueAverage): self
    {
        $this->bestLineupValueAverage = $bestLineupValueAverage;

        return $this;
    }
}
