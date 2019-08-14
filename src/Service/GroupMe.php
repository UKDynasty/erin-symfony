<?php
namespace App\Service;

use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerInterface;

class GroupMe
{
    private $token;
    private $groupMeBotId;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $groupMeLeagueChatGroupId;

    public function __construct($groupMeDirectMessageToken, $groupMeBotId, $groupMeLeagueChatGroupId, LoggerInterface $logger)
    {
        $this->token = $groupMeDirectMessageToken;
        $this->groupMeBotId = $groupMeBotId;
        $this->logger = $logger;
        $this->groupMeLeagueChatGroupId = $groupMeLeagueChatGroupId;
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

    public function getMessages(string $afterId = null, int $limit = 20)
    {
        $client = new Client();
        $url = sprintf("https://api.groupme.com/v3/groups/%s/messages?token=%s", $this->groupMeLeagueChatGroupId, $this->token);
        if ($afterId) {
            $url .= sprintf('&after_id=%s', $afterId);
        }
        $res = $client->get($url);
        $contents = json_decode($res->getBody()->getContents(), true);

        return $contents['response']['messages'];
    }

    public function sendDirectMessage(DirectMessage $directMessage)
    {
        $client = new Client();

        $url = "https://api.groupme.com/v3/direct_messages?token=" . $this->token;

        try {
            $client->request('POST', $url, [
                RequestOptions::JSON => [
                    "direct_message" => [
                        "source_guid" => uniqid(),
                        "recipient_id" => $directMessage->getRecipientId(),
                        "text" => $directMessage->getText(),
                    ]
                ]
            ]);
        } catch (BadResponseException $exception) {
            $this->logger->error('Bad response from GroupMe when sending direct message', ['directMessage' => json_encode($directMessage), 'response' => $exception->getResponse()->getBody()->getContents()]);
        }
    }

    public function sendGroupMessage(GroupMessage $groupMessage)
    {
        $client = new Client();

        $url = "https://api.groupme.com/v3/bots/post";

        try {
            $client->request('POST', $url, [
                RequestOptions::JSON => [
                    "source_guid" => uniqid(),
                    "bot_id" => $this->groupMeBotId,
                    "text" => $groupMessage->getText(),
                ]
            ]);
        } catch (BadResponseException $exception) {
            $this->logger->error('Bad response from GroupMe when sending group message', ['groupMessage' => json_encode($groupMessage), 'response' => $exception->getResponse()->getBody()->getContents()]);
        }
    }
}
