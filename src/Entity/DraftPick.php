<?php
namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="DraftPickRepository") */
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

    public function __toString()
    {
        if ($this->number) {
            $pickText = sprintf("%s %s.%s", $this->getDraft()->getYear(), $this->getRound(), str_pad($this->getNumber(), 2, "0", STR_PAD_LEFT));
        } else {
            $pickText = sprintf("%s %s%s", $this->getDraft()->getYear(), $this->getRound(), date("S", mktime(0, 0, 0, 0, $this->getRound(), 0)));
        }
        if ($this->owner !== $this->originalOwner) {
            $pickText .= sprintf(" (from the %s)", $this->getOriginalOwner()->getName());
        }
        return $pickText;
    }

}