<?php
namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity() */
class DraftPick
{
    use IdTrait;

    /**
     * @var Draft
     * @ORM\ManyToOne(targetEntity="Draft", inversedBy="picks")
     */
    private $draft;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $round;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $overall;

    /**
     * @var Franchise
     * @ORM\ManyToOne(targetEntity="Franchise")
     */
    private $originalOwner;

    /**
     * @var Franchise
     * @ORM\ManyToOne(targetEntity="Franchise", inversedBy="draftPicks")
     */
    private $owner;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     */
    private $player;

    /**
     * @return Draft
     */
    public function getDraft(): Draft
    {
        return $this->draft;
    }

    /**
     * @param Draft $draft
     */
    public function setDraft(Draft $draft): void
    {
        $this->draft = $draft;
    }

    /**
     * @return int
     */
    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @param int $round
     */
    public function setRound(int $round): void
    {
        $this->round = $round;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getOverall(): int
    {
        return $this->overall;
    }

    /**
     * @param int $overall
     */
    public function setOverall(int $overall): void
    {
        $this->overall = $overall;
    }

    /**
     * @return Franchise
     */
    public function getOriginalOwner(): Franchise
    {
        return $this->originalOwner;
    }

    /**
     * @param Franchise $originalOwner
     */
    public function setOriginalOwner(Franchise $originalOwner): void
    {
        $this->originalOwner = $originalOwner;
    }

    /**
     * @return Franchise
     */
    public function getOwner(): Franchise
    {
        return $this->owner;
    }

    /**
     * @param Franchise $owner
     */
    public function setOwner(Franchise $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

}