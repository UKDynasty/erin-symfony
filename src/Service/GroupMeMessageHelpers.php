<?php
namespace App\Service;

use App\Entity\GroupMeMessage;
use App\Entity\Owner;
use Doctrine\ORM\EntityManagerInterface;

class GroupMeMessageHelpers
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persistNewFromApiMessage(array $apiMessage)
    {
        $message = new GroupMeMessage();
        $sender = $this->entityManager->getRepository(Owner::class)->findOneBy([
            'groupMeUserId' => $apiMessage['sender_id']
        ]);
        if (!$sender) {
            $sender = new Owner();
            $sender->setName($apiMessage['name']);
            $sender->setGroupMeUserId($apiMessage['sender_id']);
            $sender->setArchived(true);
            $this->entityManager->persist($sender);
        }
        $message->setSender($sender);
        $message->setText($apiMessage['text'] ?? null);
        $message->setCreatedAt(new \DateTime('@'. $apiMessage['created_at'], new \DateTimeZone('UTC')));
        $message->setGroupMeMessageId($apiMessage['id']);
        $this->entityManager->persist($message);
        $this->entityManager->flush();
    }
}