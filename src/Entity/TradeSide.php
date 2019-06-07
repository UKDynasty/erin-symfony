<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeSideRepository")
 */
class TradeSide
{
    use IdTrait;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->picks = new ArrayCollection();
    }

    /**
     * @var Trade
     * @ORM\ManyToOne(targetEntity="Trade", inversedBy="sides")
     */
    private $trade;

    /**
     * @var Franchise
     * @ORM\ManyToOne(targetEntity="Franchise")
     */
    private $franchise;

    /**
     * @var Collection|Player[]
     * @ORM\ManyToMany(targetEntity="Player")
     */
    private $players;

    /**
     * @var Collection|DraftPick[]
     * @ORM\ManyToMany(targetEntity="DraftPick")
     */
    private $picks;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @return Trade
     */
    public function getTrade(): Trade
    {
        return $this->trade;
    }

    /**
     * @param Trade $trade
     */
    public function setTrade(Trade $trade): void
    {
        $this->trade = $trade;
    }

    /**
     * @return Franchise
     */
    public function getFranchise(): Franchise
    {
        return $this->franchise;
    }

    /**
     * @param Franchise $franchise
     */
    public function setFranchise(Franchise $franchise): void
    {
        $this->franchise = $franchise;
    }

    /**
     * @return Player[]|Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }
    }

    /**
     * @return DraftPick[]|Collection
     */
    public function getPicks()
    {
        return $this->picks;
    }

    /**
     * @param $pick
     */
    public function addPick(DraftPick $pick): void
    {
        if (!$this->picks->contains($pick)) {
            $this->picks->add($pick);
        }
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value)
    {
        $this->value = $value;
    }
}
