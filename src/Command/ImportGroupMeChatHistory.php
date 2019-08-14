<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\GroupMeMessage;
use App\Entity\GroupMeMessageImageAttachment;
use App\Entity\Player;
use App\Service\GroupMe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportGroupMeChatHistory extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var GroupMe
     */
    private $groupMe;

    public function __construct(GroupMe $groupMe, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->groupMe = $groupMe;
    }

    public function configure()
    {
        $this
            ->setName("app:syncchat")
            ->setDescription("Index GroupMe messages from the league chat")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Get the most recent message ID from the database
        $latestMessageId = null;

        $messages = $this->groupMe->getMessages($latestMessageId);
        foreach($messages as $apiMessage) {
            $message = new GroupMeMessage();
            $message->setMessageId($apiMessage['id']);
            $message->setSourceGuid($apiMessage['source_guid']);
            $message->setCreatedAt(new \DateTime('@' . $apiMessage['created_at'], new \DateTimeZone('UTC')));
            $message->setUserId($apiMessage['user_id']);
            $message->setName($apiMessage['name']);
            $message->setAvatarUrl($apiMessage['avatar_url']);
            $message->setText($apiMessage['text']);
            $message->setSystem($apiMessage['system']);
            $this->em->persist($message);

            // TODO: favourited by

            foreach($apiMessage['attachments'] as $attachment) {
                if ($attachment['type'] !== 'image') {
                    continue;
                }

                $imageAttachment = new GroupMeMessageImageAttachment();
                $imageAttachment->setMessage($message);
                $imageAttachment->setUrl($attachment['url']);
                $this->em->persist($imageAttachment);
            }
        }

        $this->em->flush();
    }
}
