<?php
namespace App\Service;

use App\Entity\Franchise;
use App\Entity\Owner;
use App\Entity\Player;
use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Erin
{
    private const IDENTIFIABLE_MESSAGES = [
        "/hello/" => "hello",
        "/hi/" => "hi",
        "/\bpick\b/i" => "pick",
        "/\bpicks\b/i" => "picks",
        "/\bthanks\b/i" => "thanks",
        "/\broster\b/i" => "roster",
        "/\bwho owns\b/i" => "whoOwns",
    ];
    /**
     * @var GroupMe
     */
    private $groupMe;
    /**
     * @var Picks
     */
    private $picks;
    /**
     * @var MessageDataExtractor
     */
    private $messageDataExtractor;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(GroupMe $groupMe, Picks $picks, MessageDataExtractor $messageDataExtractor, EntityManagerInterface $em)
    {
        $this->groupMe = $groupMe;
        $this->picks = $picks;
        $this->messageDataExtractor = $messageDataExtractor;
        $this->em = $em;
    }

    public function getOwnerFromMessageSenderId($senderId)
    {
        return $this->em->getRepository(Owner::class)->findOneBy([
            "groupMeUserId" => $senderId,
        ]);
    }

    public function receiveDirectMessage($groupMeMessage)
    {

        if ($groupMeMessage["sender_id"] === "47997687" || $groupMeMessage["sender_id"] === "585743") {
            return false;
        }

        $replyText = $this->parseMessage($groupMeMessage);

        $reply = new DirectMessage();
        $reply->setRecipientId($groupMeMessage["sender_id"]);
        if (($owner = $this->getOwnerFromMessageSenderId($groupMeMessage["sender_id"])) && (0 === rand(0,5))) {
            $replyText = "Hey " . $owner->getName() . ". " . $replyText;
        }
        $reply->setText($replyText);
        $this->groupMe->sendDirectMessage($reply);
        return true;
    }

    public function receiveGroupMessage($groupMeMessage)
    {

        if ($groupMeMessage["sender_id"] === "47997687" || $groupMeMessage["sender_id"] === "585743") {
            return false;
        }

        if (!preg_match("/^erin/i", $groupMeMessage["text"]) && !preg_match("/erin\??$/i", $groupMeMessage["text"])) {
            return false;
        }

        $replyText = $this->parseMessage($groupMeMessage);

        $reply = new DirectMessage();
        $reply->setRecipientId($groupMeMessage["sender_id"]);
        if (($owner = $this->getOwnerFromMessageSenderId($groupMeMessage["sender_id"])) && (0 === rand(0,5))) {
            $replyText = "Hey " . $owner->getName() . ". " . $replyText;
        }
        $reply = new GroupMessage();
        $reply->setText($replyText);
        $this->groupMe->sendGroupMessage($reply);
    }

    private function parseMessage(array $groupMeMessage)
    {
        foreach(static::IDENTIFIABLE_MESSAGES as $pattern => $callback) {
            if (preg_match($pattern, $groupMeMessage["text"])) {
                return $this->$callback($groupMeMessage);
            }
        }
        return "I don't know";
    }

    private function hello()
    {
        return "Oh hey there";
    }

    private function hi()
    {
        return "Oh hi there";
    }

    private function pick($message)
    {

        preg_match("/(\d+)\.(\d+)/", $message["text"], $matches);

        if (!$matches) {
            return "I can't work out which pick you mean - make sure you're formatting it as round.number - e.g. pick 2.02";
        }

        $round = (int)$matches[1];
        $pick = (int)$matches[2];
        $overall = (($round-1)*12) + $pick;

        $owner = $this->picks->getPickOwner($overall);

        if ($owner) {
            return sprintf("The %s currently own pick %s.%s", $owner, $round, str_pad($pick, 2, "0", STR_PAD_LEFT));
        } else {
            return "That pick's not set up properly on the spreadsheet - commish messed up somehow, or that's a non-existent pick.";
        }
    }

    private function picks($message)
    {
        // Identify the franchise that's mentioned in the message
        $franchise = $this->messageDataExtractor->extractFranchiseName($message["text"]);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        // If we have a canonical franchise name, get a list of their picks and return it
        $picks = $this->picks->getPicksList($franchise);
        return "Picks for the " . $franchise . ":\n\n" . implode("\n", $picks);
    }

    private function roster($message)
    {
        // Identify the franchise that's mentioned in the message
        $franchise = $this->messageDataExtractor->extractFranchise($message["text"]);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        $players = $this->em->getRepository(Player::class)->getPlayersForFranchiseOrdered($franchise);
        return sprintf(
            "Roster for the %s: \n\n%s\n\n(%s players)",
            $franchise->getName(),
            implode(
                "\n",
                array_map(
                    function($player) {
                        return sprintf("%s, %s", $player->getName(), $player->getPosition()->getName());
                    },
                    $players
                )
            ),
            count($players)
        );
    }

    private function thanks($message)
    {
        $messages = ["No problem!", "Happy to help.", "Don't mention it!"];
        return $messages[array_rand($messages)];
    }

    private function whoOwns($message)
    {
        preg_match("/\bwho owns\b(.*)/", rtrim(trim($message["text"]), "?"), $matches);
        if ($matches) {
            $playerName = trim($matches[1]);
            $explodedName = explode(" ", $playerName);
            /** @var ArrayCollection $results */
            $results = $this->em->getRepository(Player::class)->findBy([
                "firstName" => $explodedName[0],
                "lastName" => $explodedName[1],
            ]);
            if (0 === count($results)) {
                return "I can't find a player by that name, sorry.";
            }
            if (1 === count($results)) {
                if ($results[0]->getFranchise()) {
                    return sprintf("%s is owned by the %s", $results[0]->getName(), $results[0]->getFranchise()->getName());
                }
                return sprintf("%s is a free agent", $results[0]->getName());
            }
            // There is more than one match (this is unlikely)
            return "There's more than one player that matches that name. I'm not clever enough to continue.";
        }
        return "I can't understand that message. Keep it simple - just ask \"who owns Player Name?\"";
    }
}