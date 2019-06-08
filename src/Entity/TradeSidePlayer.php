<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TradeSidePlayerRepository")
 */
class TradeSidePlayer
{
    use IdTrait;

    public function __construct(TradeSide $tradeSide, Player $player, int $value)
    {
        $this->side = $tradeSide;
        $this->player = $player;
        $this->value = $value;
    }

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @var TradeSide
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TradeSide")
     * @ORM\JoinColumn(nullable=false)
     */
    private $side;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $value;

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
