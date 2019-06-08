<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeSideDraftPickRepository")
 */
class TradeSideDraftPick
{
    use IdTrait;

    public function __construct(TradeSide $tradeSide, DraftPick $pick, int $value)
    {
        $this->side = $tradeSide;
        $this->pick = $pick;
        $this->value = $value;
    }

    /**
     * @var DraftPick
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\DraftPick")
     */
    private $pick;

    /**
     * @var TradeSide
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TradeSide")
     */
    private $side;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @return DraftPick
     */
    public function getPick(): DraftPick
    {
        return $this->pick;
    }

    /**
     * @param DraftPick $pick
     */
    public function setPick(DraftPick $pick): void
    {
        $this->pick = $pick;
    }

    /**
     * @return TradeSide
     */
    public function getSide(): TradeSide
    {
        return $this->side;
    }

    /**
     * @param TradeSide $side
     */
    public function setSide(TradeSide $side): void
    {
        $this->side = $side;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
