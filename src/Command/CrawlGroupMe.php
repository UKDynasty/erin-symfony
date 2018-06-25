<?php
namespace App\Command;

use App\Service\GroupMe;
use App\Service\GroupMeMessageHelpers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlGroupMe extends Command
{
    /**
     * @var GroupMe
     */
    private $groupMe;
    /**
     * @var GroupMeMessageHelpers
     */
    private $groupMeMessageHelpers;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(?string $name = null, GroupMe $groupMe, GroupMeMessageHelpers $groupMeMessageHelpers, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->groupMe = $groupMe;
        $this->groupMeMessageHelpers = $groupMeMessageHelpers;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:crawl_messages')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->groupMe->getGroupMessagesChunkRecent();
        foreach($messages['messages'] as $message) {
            $this->groupMeMessageHelpers->persistNewFromApiMessage($message);
        }
    }
}