<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupMeMessageRepository")
 */
class GroupMeMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $messageId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sourceGuid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatarUrl;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $system;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupMeMessageImageAttachment", mappedBy="message", orphanRemoval=true)
     */
    private $imageAttachments;

    public function __construct()
    {
        $this->imageAttachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    public function getSourceGuid(): ?string
    {
        return $this->sourceGuid;
    }

    public function setSourceGuid(string $sourceGuid): self
    {
        $this->sourceGuid = $sourceGuid;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSystem(): ?bool
    {
        return $this->system;
    }

    public function setSystem(bool $system): self
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return Collection|GroupMeMessageImageAttachment[]
     */
    public function getImageAttachments(): Collection
    {
        return $this->imageAttachments;
    }

    public function addImageAttachment(GroupMeMessageImageAttachment $imageAttachment): self
    {
        if (!$this->imageAttachments->contains($imageAttachment)) {
            $this->imageAttachments[] = $imageAttachment;
            $imageAttachment->setMessage($this);
        }

        return $this;
    }

    public function removeImageAttachment(GroupMeMessageImageAttachment $imageAttachment): self
    {
        if ($this->imageAttachments->contains($imageAttachment)) {
            $this->imageAttachments->removeElement($imageAttachment);
            // set the owning side to null (unless already changed)
            if ($imageAttachment->getMessage() === $this) {
                $imageAttachment->setMessage(null);
            }
        }

        return $this;
    }
}
