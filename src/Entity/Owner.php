<?php
namespace App\Entity;

use App\Entity\Franchise;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdTrait;

/**
 * Class Owner
 * @package App\Entity
 * @ORM\Entity()
 */
class Owner
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Franchise
     *
     * @ORM\OneToOne(targetEntity="Franchise", mappedBy="owner")
     */
    private $franchise;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $groupMeUserId;

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
     * @return string|null
     */
    public function getGroupMeUserId(): ?string
    {
        return $this->groupMeUserId;
    }

    /**
     * @param string|null $groupMeUserId
     */
    public function setGroupMeUserId(?string $groupMeUserId): void
    {
        $this->groupMeUserId = $groupMeUserId;
    }
}