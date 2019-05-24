<?php
namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="DraftRepository") */
class Draft
{
    use IdTrait;

    public function __construct()
    {
        $this->picks = new ArrayCollection();
    }

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="DraftPick", mappedBy="draft")
     */
    private $picks;

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getPicks()
    {
        return $this->picks;
    }

    public function __toString()
    {
        return sprintf('%s (id %s)', $this->getYear(), $this->getId());
    }
}
