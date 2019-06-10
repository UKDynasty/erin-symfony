<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FranchiseSnapshotBestLineupPlayerRepository")
 */
class FranchiseSnapshotBestLineupPlayer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FranchiseSnapshot", inversedBy="bestLineup")
     * @ORM\JoinColumn(nullable=false)
     */
    private $snapshot;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSnapshot(): ?FranchiseSnapshot
    {
        return $this->snapshot;
    }

    public function setSnapshot(?FranchiseSnapshot $snapshot): self
    {
        $this->snapshot = $snapshot;

        return $this;
    }
}
