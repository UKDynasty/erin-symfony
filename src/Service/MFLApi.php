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
    /**
     * @var string
     */
    private $mflApiKey;

    public function __construct(int $mflYear, string $mflLeagueId, string $mflApiKey)
    {
        $this->client = new Client();
        $this->year = $mflYear;
        $this->mflLeagueId = $mflLeagueId;
        $this->mflApiKey = $mflApiKey;
    }

    private function request($url)
    {
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $responseBody = $res->getBody();
        return json_decode($responseBody, true);
    }

    public function getPlayers(array $playerIds = [])
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=players&DETAILS=1&JSON=1', $this->year);
        if (\count($playerIds) > 0) {
            $url .= '&PLAYERS=' . implode(',', $playerIds);
        }
        $res = $this->client->request('GET', $url);
        if (200 === $res->getStatusCode()) {
            $responseBody = $res->getBody();
            $jsonResponseBody = json_decode($responseBody, true);
            if (!isset($jsonResponseBody['players']['player'])) {
                throw new \Exception('No players key in response from MFL API - either you passed non-existent player IDs, or the API is returning an unexpected response.');
            }
            return $jsonResponseBody['players']['player'];
        } else {
            throw new \Exception();
        }
    }

    public function getRoster(string $leagueId, string $rosterId)
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=rosters&L=%s&APIKEY=&FRANCHISE=%s&JSON=1', $this->year, $leagueId, $rosterId);

        $res = $this->request($url);

        return $res['rosters']['franchise']['player'];
    }

    public function getRosters()
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=rosters&L=%s&JSON=1', $this->year, $this->mflLeagueId);

        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['rosters']['franchise'];
    }

    public function getTradeBait()
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=tradeBait&L=%s&INCLUDE_DRAFT_PICKS=0&JSON=1', $this->year, $this->mflLeagueId);
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        // Bug fix: if there is only 1 trade bait returned by the API, it's not an array,
        // it's just that one trade bait. Thanks MFL.
        if (array_key_exists('willGiveUp', $json['tradeBaits']['tradeBait'])) {
            return [$json['tradeBaits']['tradeBait']];
        }
        return $json['tradeBaits']['tradeBait'];
    }

    public function getAssets()
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=assets&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['assets']['franchise'];
    }

    /**
     * @return array
     */
    public function getDraftResults(): array
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=draftResults&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['draftResults']['draftUnit']['draftPick'];
    }

    /**
     * @return array
     */
    public function getTrades(): array
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=transactions&TRANS_TYPE=TRADE&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        // Bug fix: if there is only 1 trade returned by the API, it's not an array,
        // it's just that one trade. Thanks MFL.
        if (array_key_exists('franchise1_gave_up', $json['transactions']['transaction'])) {
            return [$json['transactions']['transaction']];
        }
        return $json['transactions']['transaction'];
    }

    /**
     * @return array
     */
    public function getSchedule(): array
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=schedule&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['schedule']['weeklySchedule'];
    }

    /**
     * @param int|null $week
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLiveScoring(int $week = null): array
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=liveScoring&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        if ($week !== null) {
            $url .= '&W=' . $week;
        }
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['liveScoring'];
    }

    public function getWeeklyResults(int $week = null): array
    {
        $url = sprintf('https://api.myfantasyleague.com/%s/export?TYPE=weeklyResults&L=%s&JSON=1&APIKEY=%s', $this->year, $this->mflLeagueId, $this->mflApiKey);
        if ($week !== null) {
            $url .= '&W=' . $week;
        }
        $res = $this->client->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'UKDERIN',
            ]
        ]);
        $resBody = $res->getBody();
        $json = json_decode($resBody, true);
        return $json['weeklyResults'];
    }
}
