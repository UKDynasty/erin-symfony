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
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @var integer|null
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
     * @var Player|null
     * @ORM\ManyToOne(targetEntity="Player")
     */
    private $player;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pickMadeAt;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    private $value;

    /**
     * @return \DateTime|null
     */
    public function getPickMadeAt(): ?\DateTime
    {
        return $this->pickMadeAt;
    }

    /**
     * @param \DateTime|null $pickMadeAt
     */
    public function setPickMadeAt(?\DateTime $pickMadeAt): void
    {
        $this->pickMadeAt = $pickMadeAt;
    }

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
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int|null $number
     */
    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int|null
     */
    public function getOverall(): ?int
    {
        return $this->overall;
    }

    /**
     * @param int|null $overall
     */
    public function setOverall(?int $overall): void
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
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @param Player|null $player
     */
    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param int|null $value
     */
    public function setValue(?int $value): void
    {
        $this->value = $value;
    }

    /**
     * Return the shorthand
     * @return string
     */
    public function getPickText()
    {
        if ($this->number) {
            return sprintf(
                "%s.%s",
                $this->getRound(),
                str_pad($this->getNumber(), 2, "0", STR_PAD_LEFT)
            );
        }
        return sprintf(
            "%s %s%s",
            $this->getDraft()->getYear(),
            $this->getRound(),
            date("S", mktime(0, 0, 0, 0, $this->getRound(), 0)
            )
        );
    }

    public function getPickTextIncludingOriginalOwner()
    {
        $pickText = $this->getPickText();
        if ($this->owner !== $this->originalOwner) {
            $pickText .= sprintf(" (from the %s)", $this->getOriginalOwner()->getName());
        }
        return $pickText;
    }

    public function getPickTextIncludingOwnerAndOriginalOwner()
    {
        $pickText = $this->getPickText();
        $pickText .= ": " . $this->getOwner()->getName();
        if ($this->owner !== $this->originalOwner) {
            $pickText .= sprintf(" (from the %s)", $this->getOriginalOwner()->getName());
        }
        return $pickText;
    }

    public function __toString()
    {
        return $this->getPickTextIncludingOriginalOwner();
    }

}
