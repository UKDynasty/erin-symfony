<?php
namespace App\Service;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use App\Entity\Matchup;
use App\Entity\Owner;
use App\Entity\Player;
use App\Entity\Trade;
use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
use App\Service\MFL\UrlProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class Erin
{
    private const IDENTIFIABLE_MESSAGES = [
        "/\bround\b/i" => 'picksRound',
        "/\bpick\b/i" => 'pick',
        "/\bpicks\b/i" => 'picks',
        "/\bthanks\b/i" => 'thanks',
        "/\broster\b/i" => 'roster',
        "/\bowns\b/i" => 'whoOwns',
        "/\bbait\b/i" => 'tradeBait',
        "/\bclock\b/i" => 'clock',
        '/\blottery\b/i' => 'lottery',
        '/\bbirthday\b/i' => 'birthdays',
        '/\bbirthdays\b/i' => 'birthdays',
        '/\bvalue\b/i' => 'value',
        '/hello/' => 'hello',
        '/hi/' => 'hi',
        '/\blast trade\b/i' => 'trade',
        '/\bmatchup\b/i' => 'matchup',            # Rundown of the matchups/scores if started for the current week
        '/\bmatchups\b/i' => 'matchups',            # Rundown of the matchups/scores if started for the current week
        '/\bscore\b/i' => 'matchup',              # Rundown of the matchups/scores if started for the current week
        '/\bscores\b/i' => 'matchups',              # Rundown of the matchups/scores if started for the current week
//        '/\bmatchup\b/i' => 'matchup',          # Who are franchise X playing this week? Score if started
    ];
    /**
     * @var GroupMe
     */
    private $groupMe;
    /**
     * @var MessageDataExtractor
     */
    private $messageDataExtractor;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var HumanReadableHelpers
     */
    private $helpers;
    /**
     * @var DraftManager
     */
    private $draftManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var UrlProvider
     */
    private $mflUrlProvider;
    /**
     * @var ScheduleManager
     */
    private $scheduleManager;

    public function __construct(GroupMe $groupMe, MessageDataExtractor $messageDataExtractor, EntityManagerInterface $em, HumanReadableHelpers $helpers, DraftManager $draftManager, LoggerInterface $logger, UrlProvider $mflUrlProvider, ScheduleManager $scheduleManager)
    {
        $this->groupMe = $groupMe;
        $this->messageDataExtractor = $messageDataExtractor;
        $this->em = $em;
        $this->helpers = $helpers;
        $this->draftManager = $draftManager;
        $this->logger = $logger;
        $this->mflUrlProvider = $mflUrlProvider;
        $this->scheduleManager = $scheduleManager;
    }

    public function getOwnerFromMessageSenderId($senderId)
    {
        return $this->em->getRepository(Owner::class)->findOneBy([
            'groupMeUserId' => $senderId,
        ]);
    }

    public function receiveSandboxMessage(string $message) : string
    {
        // Mock a $groupMeMessage
        $mockGroupMeMessage = [
            'sender_id' => 'sandbox',
            'text' => $message,
        ];
        return $this->parseMessage($mockGroupMeMessage);
    }

    public function receiveAlexaMessage(string $message) : string
    {
        // Mock a $groupMeMessage
        $mockGroupMeMessage = [
            'sender_id' => 'alexa',
            'text' => $message,
        ];
        return $this->parseMessage($mockGroupMeMessage);
    }

    /**
     * @param $groupMeMessage
     * @return bool
     * @throws \Exception
     */
    public function receiveDirectMessage($groupMeMessage)
    {
        if ($groupMeMessage['sender_id'] === '47997687' || $groupMeMessage['sender_id'] === '585743') {
            return false;
        }

        $replyText = $this->parseMessage($groupMeMessage);

        $reply = new DirectMessage();
        $reply->setRecipientId($groupMeMessage['sender_id']);
        if ((0 === random_int(0,5)) && ($owner = $this->getOwnerFromMessageSenderId($groupMeMessage['sender_id']))) {
            $replyText = 'Hey ' . $owner->getName() . '. ' . $replyText;
        }
        $reply->setText($replyText);
        $this->groupMe->sendDirectMessage($reply);
        return true;
    }

    public function receiveGroupMessage($groupMeMessage): ?bool
    {
        if (!preg_match("/^erin/i", $groupMeMessage["text"]) && !preg_match("/erin\??$/i", $groupMeMessage["text"])) {
            return false;
        }

        if (!0 === stripos($groupMeMessage['text'], 'erin') && !preg_match("/erin\??$/i", $groupMeMessage['text'])) {
            return false;
        }

        $replyText = $this->parseMessage($groupMeMessage);

        if (($owner = $this->getOwnerFromMessageSenderId($groupMeMessage['sender_id'])) && (0 === rand(0,5))) {
            $replyText = 'Hey ' . $owner->getName() . '. ' . $replyText;
        }
        $reply = new GroupMessage();
        $reply->setText($replyText);
        return $this->groupMe->sendGroupMessage($reply);
    }

    private function parseMessage(array $groupMeMessage)
    {
        foreach(static::IDENTIFIABLE_MESSAGES as $pattern => $callback) {
            if (preg_match($pattern, $groupMeMessage['text'])) {
                return $this->$callback($groupMeMessage);
            }
        }
        return "I don't know";
    }

    private function hello()
    {
        return 'Oh hey there';
    }

    private function hi()
    {
        return 'Oh hi there';
    }


    /**
     * @param $message
     * @return string
     */
    private function pick($message)
    {
//        return "The draft order isn't set yet!";
        preg_match("/(\d+)\.(\d+)/", $message['text'], $matches);

        if (!$matches) {
            return "I can't work out which pick you mean - make sure you're formatting it as round.number - e.g. pick 2.02";
        }

        $round = (int)$matches[1];
        $number = (int)$matches[2];

        $pick = $this->em->getRepository(DraftPick::class)->findOneBy([
            'draft' => $this->draftManager->getCurrentDraft(),
            'round' => $round,
            'number' => $number,
        ]);

        if ($pick) {
            return sprintf('The %s currently own pick %s.%s', $pick->getOwner()->getName(), $round, str_pad($pick->getNumber(), 2, '0', STR_PAD_LEFT));
        }

        return "That pick doesn't exist in the database. Ask the commish what's going on.";
    }

    private function picks($message)
    {
        // Identify the franchise that's mentioned in the message
        $franchise = $this->messageDataExtractor->extractFranchise($message['text']);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        // If we have a franchise, get a list of their picks and return it

        /** @var DraftPick[] $picks */
        $picks = $this->em->getRepository(DraftPick::class)->getUnusedPicksForFranchise($franchise);

        return 'Picks for the ' . $franchise->getName() . ":\n\n" . implode("\n", $picks);
    }

    private function picksRound($message)
    {
        $draft = $this->draftManager->getCurrentDraft();

        preg_match('/\d+/',$message['text'],$matches);
        if (!$matches) {
            return 'Which round do you want to see the draft order for? Say something like "Show me the picks for round 3"';
        }
        $round = $matches[0];

        /** @var DraftPick[] $picks */
        $picks = $this->em->getRepository(DraftPick::class)->findBy([
            'draft' => $draft,
            'round' => $round,
        ], ['number' => 'ASC']);

        return sprintf("%s Draft Round %s:\n\n%s", $draft->getYear(), $round, $this->helpers->roundOfPicksToList($picks));
    }

    private function roster($message)
    {
//        return 'Sorry, I can\'t do this anymore. I can only answer this question if we\'re using MyFantasyLeague.';
        // Identify the franchise that's mentioned in the message
        $franchise = $this->messageDataExtractor->extractFranchise($message['text']);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        $players = $this->em->getRepository(Player::class)->getPlayersForFranchiseOrdered($franchise);
        return sprintf(
            "Roster for the %s (%s players): \n\n%s\n\n%s",
            $franchise->getName(),
            count($players),
            $this->helpers->playersToPositionSeparatedList($players),
            $this->mflUrlProvider->franchiseRoster($franchise)
        );
    }

    private function tradeBait($message)
    {
//        return 'Sorry, I can\'t do this anymore. I can only answer this question if we\'re using MyFantasyLeague.';
        // Identify the franchise that's mentioned in the message
        $franchise = $this->messageDataExtractor->extractFranchise($message['text']);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        $players = $this->em->getRepository(Player::class)->getTradeBaitByFranchiseOrdered($franchise);

        if (0 === count($players)) {
            return sprintf("%s don't currently have any players listed as trade bait.", $franchise->getName());
        }

        return sprintf(
            "Trade bait for the %s: \n\n%s",
            $franchise->getName(),
            $this->helpers->playersToList($players)
        );
    }

    private function thanks($message)
    {
        $messages = ['No problem!', 'Happy to help.', "Don't mention it!"];
        return $messages[array_rand($messages)];
    }

    private function lottery($message)
    {
        $numbers = array_rand(array_flip(range(1,59)),7);
        return sprintf(
            "My crystal ball can reveal the winning lottery numbers:\n\n%s, Bonus Ball: %s",
            implode(', ', array_slice($numbers, 0, 6)),
            $numbers[6]
        );
    }

    private function clock($message)
    {
//        return 'There isn\'t a draft currently running.';
        $pickOnClock = $this->draftManager->getPickOnClock($this->draftManager->getCurrentDraft());
        if ($pickOnClock instanceof DraftPick) {
            return sprintf('The %s are on the clock with pick %s.', $pickOnClock->getOwner()->getName(), $pickOnClock->getPickText());
        }
        return 'The draft is over, guys. Better luck next year.';
    }

    private function birthdays()
    {
        $players = $this->em->getRepository(Player::class)->getPlayersWithBirthdayToday();

        if (count($players) < 1) {
            return 'No players are celebrating their birthday today. How sad.';
        }

        return "Players celebrating their birthday today: \n\n" . implode("\n", array_map(function(Player $player) {
            return sprintf('%s - %s (%s)', $player->getName(), $player->getAge(), $player->getFranchise() ? $player->getFranchise()->getName() : 'FA');
        }, $players));
    }

    private function whoOwns($message)
    {
//        return 'Sorry, I can\'t do this anymore. I can only answer this question if we\'re using MyFantasyLeague.';
        preg_match("/\bwho owns\b(.*)/i", rtrim(trim($message['text']), '?'), $matches);
        if ($matches) {
            $playerName = trim($matches[1]);
            $explodedName = explode(' ', $playerName);

            if (!isset($explodedName[1])) {
                // No players have just 1 name, and it'll cause the following block to crash
                return 'A player without a surname? Unlikely.';
            }

            /** @var ArrayCollection $results */
            $results = $this->em->getRepository(Player::class)->findBy([
                'firstName' => $explodedName[0],
                'lastName' => $explodedName[1],
            ]);
            if (0 === count($results)) {
                return "I can't find a player by that name, sorry.";
            }
            if (1 === count($results)) {
                if ($results[0]->getFranchise() && $results[0]->isListedAsTradeBait()) {
                    return sprintf('%s is owned by the %s, and is currently listed as trade bait.', $results[0]->getName(), $results[0]->getFranchise()->getName());
                }
                /** @var Franchise $franchise */
                $franchise = $results[0]->getFranchise();
                if ($franchise) {
                    if ($franchise->getOwner() === $this->getOwnerFromMessageSenderId($message['sender_id'])) {
                        return sprintf("%s is owned by the %s, but you already know that, don't you?", $results[0]->getName(), $results[0]->getFranchise()->getName());
                    }
                    return sprintf('%s is owned by the %s.', $results[0]->getName(), $results[0]->getFranchise()->getName());
                }
                return sprintf('%s is a free agent.', $results[0]->getName());
            }
            // There is more than one match (this is unlikely)
            return "There's more than one player that matches that name. I'm not clever enough to continue.";
        }
        return "I can't understand that message. Keep it simple - just ask \"who owns Player Name?\"";
    }

    private function value($message)
    {
//        return 'Sorry, I can\'t do this anymore. I can only answer this question if we\'re using MyFantasyLeague.';
        preg_match("/\bvalue\b(.*)/i", rtrim(trim($message['text']), '?'), $matches);
        if ($matches) {
            $playerName = trim($matches[1]);
            $explodedName = explode(' ', $playerName);
            /** @var ArrayCollection $results */
            $results = $this->em->getRepository(Player::class)->findBy([
                'firstName' => $explodedName[0],
                'lastName' => $explodedName[1],
            ]);
            if (0 === count($results)) {
                return "I can't find a player by that name, sorry.";
            }
            if (1 === count($results)) {
                /** @var Player $player */
                $player = $results[0];
                if ($player->getValue()) {
                    return sprintf('%s is worth %s.', $player->getName(), $player->getValue());
                }
                return "I don't know how to value " . $player->getName() . ", to be honest with you.";
            }
            // There is more than one match (this is unlikely)
            return "There's more than one player that matches that name. I'm not clever enough to continue.";
        }
        return "I can't understand that message. Keep it simple - just ask \"who owns Player Name?\"";
    }

    private function trade($message)
    {
        // Test method to be used for reporting latest trade in event-driven system
        $latestTrade = $this->em->getRepository(Trade::class)->findOneBy([], ['date' => 'DESC'], 1);
        return $this->helpers->tradeToText($latestTrade);
    }

    private function matchups($message)
    {
        $week = $this->scheduleManager->getCurrentWeek();
        $franchise = $this->messageDataExtractor->extractFranchise($message['text']);
        $matchups = $this->em->getRepository(Matchup::class)->findByWeek($week, $franchise ?? null);

        return implode("\n\n", array_map(function(Matchup $matchup) {
            return $matchup->toStringForErin();
        }, $matchups));
    }

    private function matchup($message)
    {
        $week = $this->scheduleManager->getCurrentWeek();
        $franchise = $this->messageDataExtractor->extractFranchise($message['text']);

        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about";
        }

        $matchup = $this->em->getRepository(Matchup::class)->findOneByWeekForFranchise($week, $franchise);

        $lines = [];

        foreach($matchup->getMatchupFranchises() as $matchupFranchise) {
            $lines[] = $matchupFranchise->getFranchise()->getName() . ' ' . $matchupFranchise->getScore();
            $lines[] = '';
            foreach($matchupFranchise->getMatchupPlayers() as $matchupPlayer) {
                $gameSecondsRemaining = $matchupPlayer->getGameSecondsRemaining();
                switch($gameSecondsRemaining) {
                    case 0:
                        $status = ' (F)';
                        break;
                    case 3600:
                        $status = '';
                        break;
                    default:
                        $formatted = $matchupPlayer->getGameMinutesRemainingFormatted();
                        $status = " (playing, ${formatted} remaining)";

                }

                $lines[] = $matchupPlayer->getPlayer()->getName() . ' ' . $matchupPlayer->getScore() . $status;
            }
            $lines[] = '';
        }

        return join("\n", $lines);
    }
}
