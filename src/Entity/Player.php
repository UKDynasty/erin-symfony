<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdTrait;

/** @ORM\Entity() */
class Player
{
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $draftYear;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $draftRound;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $draftPick;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $draftTeam;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jersey;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $college;

    /**
     * @var null|string
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $externalIdMfl;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdNfl;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdRotoworld;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdStats;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdStatsGlobal;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdFleaflicker;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdKffl;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdEspn;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdSportsdata;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdCbs;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdGsis;

    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $externalIdEsb;
    /**
     * @var null|string
     * @ORM\Column(type="string", nullable=true)
     */
    private $twitterHandle;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getName()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime|null $birthdate
     */
    public function setBirthdate(?\DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return int|null
     */
    public function getDraftYear(): ?int
    {
        return $this->draftYear;
    }

    /**
     * @param int|null $draftYear
     */
    public function setDraftYear(?int $draftYear): void
    {
        $this->draftYear = $draftYear;
    }

    /**
     * @return int|null
     */
    public function getDraftRound(): ?int
    {
        return $this->draftRound;
    }

    /**
     * @param int|null $draftRound
     */
    public function setDraftRound(?int $draftRound): void
    {
        $this->draftRound = $draftRound;
    }

    /**
     * @return int|null
     */
    public function getDraftPick(): ?int
    {
        return $this->draftPick;
    }

    /**
     * @param int|null $draftPick
     */
    public function setDraftPick(?int $draftPick): void
    {
        $this->draftPick = $draftPick;
    }

    /**
     * @return string|null
     */
    public function getDraftTeam(): ?string
    {
        return $this->draftTeam;
    }

    /**
     * @param string|null $draftTeam
     */
    public function setDraftTeam(?string $draftTeam): void
    {
        $this->draftTeam = $draftTeam;
    }

    /**
     * @return null|string
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param null|string $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int|null $weight
     */
    public function setWeight(?int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int|null
     */
    public function getJersey(): ?int
    {
        return $this->jersey;
    }

    /**
     * @param int|null $jersey
     */
    public function setJersey(?int $jersey): void
    {
        $this->jersey = $jersey;
    }

    /**
     * @return null|string
     */
    public function getCollege(): ?string
    {
        return $this->college;
    }

    /**
     * @param null|string $college
     */
    public function setCollege(?string $college): void
    {
        $this->college = $college;
    }

    /**
     * @return null|string
     */
    public function getExternalIdMfl(): ?string
    {
        return $this->externalIdMfl;
    }

    /**
     * @param null|string $externalIdMfl
     */
    public function setExternalIdMfl(?string $externalIdMfl): void
    {
        $this->externalIdMfl = $externalIdMfl;
    }

    /**
     * @return null|string
     */
    public function getExternalIdNfl(): ?string
    {
        return $this->externalIdNfl;
    }

    /**
     * @param null|string $externalIdNfl
     */
    public function setExternalIdNfl(?string $externalIdNfl): void
    {
        $this->externalIdNfl = $externalIdNfl;
    }

    /**
     * @return null|string
     */
    public function getExternalIdRotoworld(): ?string
    {
        return $this->externalIdRotoworld;
    }

    /**
     * @param null|string $externalIdRotoworld
     */
    public function setExternalIdRotoworld(?string $externalIdRotoworld): void
    {
        $this->externalIdRotoworld = $externalIdRotoworld;
    }

    /**
     * @return null|string
     */
    public function getExternalIdStats(): ?string
    {
        return $this->externalIdStats;
    }

    /**
     * @param null|string $externalIdStats
     */
    public function setExternalIdStats(?string $externalIdStats): void
    {
        $this->externalIdStats = $externalIdStats;
    }

    /**
     * @return null|string
     */
    public function getExternalIdStatsGlobal(): ?string
    {
        return $this->externalIdStatsGlobal;
    }

    /**
     * @param null|string $externalIdStatsGlobal
     */
    public function setExternalIdStatsGlobal(?string $externalIdStatsGlobal): void
    {
        $this->externalIdStatsGlobal = $externalIdStatsGlobal;
    }

    /**
     * @return null|string
     */
    public function getExternalIdFleaflicker(): ?string
    {
        return $this->externalIdFleaflicker;
    }

    /**
     * @param null|string $externalIdFleaflicker
     */
    public function setExternalIdFleaflicker(?string $externalIdFleaflicker): void
    {
        $this->externalIdFleaflicker = $externalIdFleaflicker;
    }

    /**
     * @return null|string
     */
    public function getExternalIdKffl(): ?string
    {
        return $this->externalIdKffl;
    }

    /**
     * @param null|string $externalIdKffl
     */
    public function setExternalIdKffl(?string $externalIdKffl): void
    {
        $this->externalIdKffl = $externalIdKffl;
    }

    /**
     * @return null|string
     */
    public function getExternalIdEspn(): ?string
    {
        return $this->externalIdEspn;
    }

    /**
     * @param null|string $externalIdEspn
     */
    public function setExternalIdEspn(?string $externalIdEspn): void
    {
        $this->externalIdEspn = $externalIdEspn;
    }

    /**
     * @return null|string
     */
    public function getExternalIdSportsdata(): ?string
    {
        return $this->externalIdSportsdata;
    }

    /**
     * @param null|string $externalIdSportsdata
     */
    public function setExternalIdSportsdata(?string $externalIdSportsdata): void
    {
        $this->externalIdSportsdata = $externalIdSportsdata;
    }

    /**
     * @return null|string
     */
    public function getExternalIdCbs(): ?string
    {
        return $this->externalIdCbs;
    }

    /**
     * @param null|string $externalIdCbs
     */
    public function setExternalIdCbs(?string $externalIdCbs): void
    {
        $this->externalIdCbs = $externalIdCbs;
    }

    /**
     * @return null|string
     */
    public function getExternalIdGsis(): ?string
    {
        return $this->externalIdGsis;
    }

    /**
     * @param null|string $externalIdGsis
     */
    public function setExternalIdGsis(?string $externalIdGsis): void
    {
        $this->externalIdGsis = $externalIdGsis;
    }

    /**
     * @return null|string
     */
    public function getExternalIdEsb(): ?string
    {
        return $this->externalIdEsb;
    }

    /**
     * @param null|string $externalIdEsb
     */
    public function setExternalIdEsb(?string $externalIdEsb): void
    {
        $this->externalIdEsb = $externalIdEsb;
    }

    /**
     * @return null|string
     */
    public function getTwitterHandle(): ?string
    {
        return $this->twitterHandle;
    }

    /**
     * @param null|string $twitterHandle
     */
    public function setTwitterHandle(?string $twitterHandle): void
    {
        $this->twitterHandle = $twitterHandle;
    }



}