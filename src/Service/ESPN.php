<?php
namespace App\Service;

use GuzzleHttp\Client;

class ESPN
{
    private const URL_BASE = 'http://games.espn.com/ffl/api/v2/scoreboard?leagueId=7168';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    private function generateUrl(array $params = [])
    {
        $url = static::URL_BASE;
        foreach ($params as $k => $v) {
            $url .= sprintf('&%s=%s', $k, $v);
        }
        return $url;
    }

    private function request($url)
    {
        $res = $this->client->request('GET', $url);
        $responseBody = $res->getBody();
        return json_decode($responseBody, true);
    }

    public function getScoreboard()
    {
        $res = $this->client->request('GET', $this->generateUrl());
        if (200 === $res->getStatusCode()) {
            $responseBody = $res->getBody();
            $jsonResponseBody = json_decode($responseBody, true);
            dump($jsonResponseBody);
            $matchups = [];
            foreach($jsonResponseBody['scoreboard']['matchups'] as $matchup) {
                $home = sprintf(
                    '%s %s %s',
                    $matchup['teams'][0]['team']['teamLocation'],
                    $matchup['teams'][0]['team']['teamNickname'],
                    $matchup['teams'][0]['score']
                );
                $away = sprintf(
                    '%s %s %s',
                    $matchup['teams'][1]['score'],
                    $matchup['teams'][1]['team']['teamLocation'],
                    $matchup['teams'][1]['team']['teamNickname']
                );
                $matchups[] = sprintf('%s - %s', $home, $away);
            }
        }
        return implode("\r\n", $matchups);
    }
}