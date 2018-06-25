<?php
namespace App\Service;

use App\GroupMe\DirectMessage;
use App\GroupMe\GroupMessage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
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
    private $groupMeGroupId;

    public function __construct($groupMeDirectMessageToken, $groupMeBotId, $groupMeGroupId, LoggerInterface $logger)
    {
        $this->token = $groupMeDirectMessageToken;
        $this->groupMeBotId = $groupMeBotId;
        $this->logger = $logger;
        $this->groupMeGroupId = $groupMeGroupId;
    }

    private function get($url)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', $url);
            $content = json_decode($response->getBody()->getContents(), true);
            if (200 === $content['meta']['code']) {
                return $content['response'];
            }
        } catch (GuzzleException $exception) {
            dump($exception);
        }
    }

    public function getGroupMembers()
    {
        $url = "https://api.groupme.com/v3/groups?token=" . $this->token;

        return $this->get($url);
    }

    public function getGroupMessagesChunkRecent()
    {
        $url = sprintf(
            'https://api.groupme.com/v3/groups/%s/messages?limit=100&token=%s',
            $this->groupMeGroupId,
            $this->token
        );

        return $this->get($url);
    }

    public function getGroupMessagesChunkBefore(int $groupMeMessageId)
    {
        $url = sprintf(
            'https://api.groupme.com/v3/groups/%s/messages?before_id=%s&limit=100&token=%s',
            $this->groupMeGroupId,
            $groupMeMessageId,
            $this->token
        );

        return $this->get($url);
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