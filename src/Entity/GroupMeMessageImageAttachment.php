<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupMeMessageImageAttachmentRepository")
 */
class GroupMeMessageImageAttachment
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupMeMessage", inversedBy="imageAttachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMessage(): ?GroupMeMessage
    {
        return $this->message;
    }

    public function setMessage(?GroupMeMessage $message): self
    {
        $this->message = $message;

        return $this;
    }
}
