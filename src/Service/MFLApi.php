<?php
namespace App\Service;

use GuzzleHttp\Client;

class MFLApi
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $year;
    /**
     * @var string
     */
    private $mflLeagueId;

    public function __construct(int $mflYear, string $mflLeagueId)
    {
        $this->client = new Client();
        $this->year = $mflYear;
        $this->mflLeagueId = $mflLeagueId;
    }

    private function request($url)
    {
        $res = $this->client->request("GET", $url);
        $responseBody = $res->getBody();
        return json_decode($responseBody, true);
    }

    public function getPlayers(array $playerIds = [])
    {
        $url = sprintf("http://www03.myfantasyleague.com/%s/export?TYPE=players&DETAILS=1&JSON=1", $this->year);
        if (count($playerIds) > 0) {
            $url .= "&PLAYERS=" . implode(",", $playerIds);
        }
        $res = $this->client->request("GET", $url);
        if (200 === $res->getStatusCode()) {
            $responseBody = $res->getBody();
            $jsonResponseBody = json_decode($responseBody, true);
            if (!isset($jsonResponseBody["players"]["player"])) {
                throw new \Exception("No players key in response from MFL API - either you passed non-existent player IDs, or the API is returning an unexpected response.");
            }
            return $jsonResponseBody["players"]["player"];
        } else {
            throw new \Exception();
        }
    }

    public function getRoster(string $leagueId, string $rosterId)
    {
        $url = sprintf("http://www66.myfantasyleague.com/%s/export?TYPE=rosters&L=%s&APIKEY=&FRANCHISE=%s&JSON=1", $this->year, $leagueId, $rosterId);

        $res = $this->request($url);

        return $res["rosters"]["franchise"]["player"];
    }

    public function getRosters()
    {
        $url = sprintf("http://www66.myfantasyleague.com/%s/export?TYPE=rosters&L=%s&JSON=1", $this->year, $this->mflLeagueId);

        $res = $this->client->request("GET", $url);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json["rosters"]["franchise"];
    }
}