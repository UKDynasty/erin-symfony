<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class GroupMeMessage
 * @package App\Entity
 * @ORM\Entity()
 */
class GroupMeMessage
{
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $groupMeMessageId;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @var Owner
     * @ORM\ManyToOne(targetEntity="App\Entity\Owner")
     */
    private $sender;

    /**
     * @var Collection|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Owner")
     */
    private $favoritedBy;

    /**
     * @return string
     */
    public function getGroupMeMessageId(): string
    {
        return $this->groupMeMessageId;
    }

    /**
     * @param string $groupMeMessageId
     */
    public function setGroupMeMessageId(string $groupMeMessageId): void
    {
        $this->groupMeMessageId = $groupMeMessageId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return Owner
     */
    public function getSender(): Owner
    {
        return $this->sender;
    }

    /**
     * @param Owner $sender
     */
    public function setSender(Owner $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getFavoritedBy()
    {
        return $this->favoritedBy;
    }
}