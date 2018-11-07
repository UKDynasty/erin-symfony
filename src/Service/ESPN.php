<?php
namespace App\Service;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Client;
use Symfony\Component\BrowserKit\Cookie;

class ESPN
{
    private const URL_BASE = 'http://games.espn.com/ffl/api/v2/';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    private function generateUrl($endpoint, array $params = [])
    {

        $params = array_merge([
            'leagueId' => '7168'
        ], $params);
        $url = static::URL_BASE;
        $qsParams = [];
        foreach ($params as $k => $v) {
            $qsParams[] = sprintf('%s=%s', $k, $v);
        }
        $qs = \implode('&', $qsParams);
        return $url . $endpoint . '?' . $qs;
    }

    private function request($url)
    {
        $cookieJar = CookieJar::fromArray([
            'espn_s2' => 'AEAEkNFwbmvnOXlpl7RlE5LtXqLDJX1XFLn08ljSPtu4rlgFrBEDeMyBqw3Vg3nUWpI7BXIw5f47Bu1Ojxw25%2Bnb48HSsYm2vh5jVdyH2i%2FmL%2FkFiqcGYfY1WIHc4PUNF%2BKKPzHNgI6Wj6r4r%2BimLpi%2FcXi5s7zofExiPPCZ25AOcLGRoIzQC5B4BrA3QJbL27Uik3qcf2%2BlUmsQgT%2BfImLb5kNyobIWHldEb1E0Dyh5VuPEsqpGgRsXM5UI7ESDSXN6A57E0Ae9r5ssl6rS7cgp',
            'SWID' => '{4549A0E8-0F52-4C4E-AD90-32C5A94D95CA}'
        ], '.espn.com');

        $res = $this->client->request('GET', $url, ['cookies' => $cookieJar]);
        $responseBody = $res->getBody();
        return json_decode($responseBody, true);
    }

    public function getScoreboard()
    {
        $res = $this->client->request('GET', $this->generateUrl('scoreboard'));
        if (200 === $res->getStatusCode()) {
            $responseBody = $res->getBody();
            $jsonResponseBody = json_decode($responseBody, true);
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

    public function getRosterInfo()
    {
        $teamIds = '1,2,3,4,5,6,7,8,9,10,11,12';
        $url = $this->generateUrl('rosterInfo', ['teamIds' => $teamIds]);
        $res = $this->request($url);
        return $res['leagueRosters']['teams'];
    }
}