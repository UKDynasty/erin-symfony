<?php
namespace App\Entity;

use App\Entity\Owner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdTrait;

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
     * @var string
     * @ORM\Column(type="string")
     */
    private $espnFranchiseId;

    /**
     * @var array
     * @ORM\Column(type="simple_array")
     */
    private $identifiers;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Player", mappedBy="franchise")
     */
    private $players;

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
     * @return string
     */
    public function getEspnFranchiseId(): string
    {
        return $this->espnFranchiseId;
    }

    /**
     * @param string $espnFranchiseId
     */
    public function setEspnFranchiseId(string $espnFranchiseId): void
    {
        $this->espnFranchiseId = $espnFranchiseId;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayers(): ArrayCollection
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
}