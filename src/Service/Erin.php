<?php
namespace App\Service;

use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
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

    public function __construct(GroupMe $groupMe, Picks $picks, MessageDataExtractor $messageDataExtractor)
    {
        $this->groupMe = $groupMe;
        $this->picks = $picks;
        $this->messageDataExtractor = $messageDataExtractor;
    }

    public function receiveDirectMessage($groupMeMessage)
    {

        if ($groupMeMessage["sender_id"] === "47997687" || $groupMeMessage["sender_id"] === "585743") {
            return false;
        }

        $replyText = $this->parseMessage($groupMeMessage["text"]);

        $reply = new DirectMessage();
        $reply->setRecipientId($groupMeMessage["sender_id"]);
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

        $replyText = $this->parseMessage($groupMeMessage["text"]);
        $reply = new GroupMessage();
        $reply->setText($replyText);
        $this->groupMe->sendGroupMessage($reply);
    }

    private function parseMessage(string $messageText)
    {
        foreach(static::IDENTIFIABLE_MESSAGES as $pattern => $callback) {
            if (preg_match($pattern, $messageText)) {
                return $this->$callback($messageText);
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

        preg_match("/(\d+)\.(\d+)/", $message, $matches);

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
        $franchise = $this->messageDataExtractor->extractFranchiseName($message);
        // If the franchise can't be identified, return a message saying as much
        if (!$franchise) {
            return "Sorry, I don't know which franchise you're asking about. I could guess, but that would be less than useful.";
        }
        // If we have a canonical franchise name, get a list of their picks and return it
        $picks = $this->picks->getPicksList($franchise);
        return "Picks for the " . $franchise . ":\n\n" . implode("\n", $picks);
    }

    private function thanks($message)
    {
        $messages = ["No problem!", "Happy to help.", "Don't mention it!"];
        return $messages[array_rand($messages)];
    }

    private function whoOwns($message)
    {

    }
}