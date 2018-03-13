<?php
namespace App\Service;

use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GroupMe
{
    private $token;
    private $groupMeBotId;

    public function __construct($groupMeDirectMessageToken, $groupMeBotId)
    {
        $this->token = $groupMeDirectMessageToken;
        $this->groupMeBotId = $groupMeBotId;
    }

    public function getGroupMembers()
    {
        $client = new Client();

        $url = "https://api.groupme.com/v3/groups?token=" . $this->token;

        $res = $client->get($url);

        echo $res->getBody()->getContents();
        exit();

        return $res->getBody();
    }

    public function sendDirectMessage(DirectMessage $directMessage)
    {
        $client = new Client();

        $url = "https://api.groupme.com/v3/direct_messages?token=" . $this->token;

        $res = $client->request('POST', $url, [
            RequestOptions::JSON => [
                "direct_message" => [
                    "source_guid" => uniqid(),
                    "recipient_id" => $directMessage->getRecipientId(),
                    "text" => $directMessage->getText(),
                ]
            ]
        ]);
    }

    public function sendGroupMessage(GroupMessage $groupMessage)
    {
        $client = new Client();

        $url = "https://api.groupme.com/v3/bots/post";

        $res = $client->request('POST', $url, [
            RequestOptions::JSON => [
                "source_guid" => uniqid(),
                "bot_id" => $this->groupMeBotId,
                "text" => $groupMessage->getText(),
            ]
        ]);
    }
}