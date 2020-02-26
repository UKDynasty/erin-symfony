<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Franchise
 * @package App\Entity
 * @ORM\Entity()
 */
class Franchise
{
    use IdTrait;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->snapshots = new ArrayCollection();
    }

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Owner
     * @ORM\OneToOne(targetEntity="Owner", inversedBy="franchise")
     */
    private $owner;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $mflFranchiseId;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $identifiers;

    /**
     * @var Collection|Player[]
     * @ORM\OneToMany(targetEntity="Player", mappedBy="franchise")
     */
    private $players;

    /**
     * @var ArrayCollection|PersistentCollection
     * @ORM\OneToMany(targetEntity="DraftPick", mappedBy="owner")
     */
    private $draftPicks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FranchiseSnapshot", mappedBy="franchise")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $snapshots;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     */
    public function setOwner(Owner $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getMflFranchiseId(): string
    {
        return $this->mflFranchiseId;
    }

    /**
     * @param string $mflFranchiseId
     */
    public function setMflFranchiseId(string $mflFranchiseId): void
    {
        $this->mflFranchiseId = $mflFranchiseId;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @return array
     */
    public function getIdentifiers(): array
    {
        return $this->identifiers;
    }

    /**
     * @param array $identifiers
     */
    public function setIdentifiers(array $identifiers): void
    {
        $this->identifiers = $identifiers;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|FranchiseSnapshot[]
     */
    public function getSnapshots(): Collection
    {
        return $this->snapshots;
    }

    public function addSnapshot(FranchiseSnapshot $snapshot): self
    {
        if (!$this->snapshots->contains($snapshot)) {
            $this->snapshots[] = $snapshot;
            $snapshot->setFranchise($this);
        }

        return $this;
    }

    public function removeSnapshot(FranchiseSnapshot $snapshot): self
    {
        if ($this->snapshots->contains($snapshot)) {
            $this->snapshots->removeElement($snapshot);
            // set the owning side to null (unless already changed)
            if ($snapshot->getFranchise() === $this) {
                $snapshot->setFranchise(null);
            }
        }

        return $this;
    }

    public function getLatestSnapshot(): ?FranchiseSnapshot
    {
        return $this->snapshots[0];
    }
}
