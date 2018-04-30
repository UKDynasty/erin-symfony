<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Player;
use App\GroupMe\GroupMessage;
use App\Service\GroupMe;
use App\Service\HumanReadableHelpers;
use App\Service\MFLApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckDraftResults extends Command
{
    /**
     * @var MFLApi
     */
    private $MFLApi;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $mflYear;
    /**
     * @var HumanReadableHelpers
     */
    private $humanReadableHelpers;
    /**
     * @var GroupMe
     */
    private $groupMe;

    public function __construct(?string $name = null, EntityManagerInterface $entityManager, MFLApi $MFLApi, HumanReadableHelpers $humanReadableHelpers, GroupMe $groupMe, int $mflYear)
    {

        $this->MFLApi = $MFLApi;
        $this->entityManager = $entityManager;
        $this->mflYear = $mflYear;
        $this->humanReadableHelpers = $humanReadableHelpers;
        $this->groupMe = $groupMe;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:checkdraftresults')
            ->setDescription('Check the MFL API for new draft results for the current year')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $draft = $this->entityManager->getRepository(Draft::class)->findOneBy(['year' => $this->mflYear]);

        $draftResults = $this->MFLApi->getDraftResults();

        foreach($draftResults as $draftResult) {
            // If the pick has been made, load it up from the database and make sure it hasn't already been processed
            $pick = $this->entityManager->getRepository(DraftPick::class)->findOneBy([
                'draft' => $draft,
                'round' => (int)$draftResult['round'],
                'number' => (int)$draftResult['pick'],
            ]);

            if (!$pick) {
                throw new \Exception('Cannot find pick in database - has the draft been created correctly?');
            }

            if ('' === $draftResult['player'] || null !== $pick->getPickMadeAt()) {
                continue;
            }

            $player = $this->entityManager->getRepository(Player::class)->findOneBy([
                'externalIdMfl' => $draftResult['player']
            ]);

            // Ensure franchise is up to date (a race condition exists if a trade was made between updateassetsdata and this command being run)
            $franchise = $this->entityManager->getRepository(Franchise::class)->findOneBy([
                'mflFranchiseId' => $draftResult['franchise']
            ]);

            $pick->setOwner($franchise);
            $pick->setPlayer($player);
            $pick->setPickMadeAt(new \DateTime('@' . $draftResult['timestamp'], new \DateTimeZone('UTC')));

            $text = sprintf(
                'With the %s pick in the %s round of the 2018 UK Dynasty draft, the %s select %s, %s, %s.',
                $this->humanReadableHelpers->ordinal($pick->getNumber()),
                $this->humanReadableHelpers->ordinal($pick->getRound()),
                $pick->getOwner()->getName(),
                $player->getName(),
                $player->getPosition()->getName(),
                $player->getTeam()
            );

            if ($player->getDraftRound() && $player->getCollege()) {
                $text .= sprintf(
                    '\n\n %s was selected with the %s pick in the %s round of the NFL Draft, out of %s.',
                    $player->getLastName(),
                    $this->humanReadableHelpers->ordinal($player->getDraftPick()),
                    $this->humanReadableHelpers->ordinal($player->getDraftRound()),
                    $player->getCollege()
                );
            }

            $groupMeMessage = new GroupMessage();
            $groupMeMessage->setText($text);
            $this->groupMe->sendGroupMessage($groupMeMessage);
        }

        $this->entityManager->flush();
    }
}