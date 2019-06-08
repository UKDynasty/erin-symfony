<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeRepository")
 */
class Trade
{
    use IdTrait;

    public function __construct()
    {
        $this->sides = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var Collection|TradeSide[]
     * @ORM\OneToMany(targetEntity="TradeSide", mappedBy="trade")
     */
    private $sides;

    /**
     * @var TradeSide
     * @ORM\ManyToOne(targetEntity="TradeSide")
     */
    private $winningSide;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valueDifference;

    /**
     * @ORM\Column(type="text")
     */
    private $mflApiHash;

    /**
     * @return TradeSide[]|Collection
     */
    public function getSides()
    {
        return $this->sides;
    }

    /**
     * @param TradeSide $side
     */
    public function addSide($side): void
    {
        if (!$this->sides->contains($side)) {
            $this->sides->add($side);
            $side->setTrade($this);
        }
    }

    /**
     * @return TradeSide
     */
    public function getWinningSide(): ?TradeSide
    {
        return $this->winningSide;
    }

    /**
     * @param TradeSide $winningSide
     */
    public function setWinningSide(TradeSide $winningSide): void
    {
        $this->winningSide = $winningSide;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date)
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getValueDifference(): float
    {
        return $this->valueDifference;
    }

    /**
     * @param float $valueDifference
     */
    public function setValueDifference(float $valueDifference): void
    {
        $this->valueDifference = $valueDifference;
    }

    public function getMflApiHash(): ?string
    {
        return $this->mflApiHash;
    }

    public function setMflApiHash(string $mflApiHash)
    {
        $this->mflApiHash = $mflApiHash;
    }
}
