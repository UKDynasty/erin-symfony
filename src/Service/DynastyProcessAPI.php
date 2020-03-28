<?php
namespace App\Service;

use GuzzleHttp\Client;

class DynastyProcessAPI
{
    /**
     * @var Client
     */
    private $client;

    const BASE_URL = 'https://dynastyprocess-api.abrey.dev';

    public function __construct()
    {
        $this->client = new Client();
    }

    private function request($url)
    {
        $res = $this->client->request('GET', $url);
        if ($res->getStatusCode() !== 200) {
            throw new \Exception();
        }
        $responseBody = $res->getBody();
        return json_decode($responseBody, true);
    }

    public function getPlayerValues()
    {
        $url = self::BASE_URL . '/player-values-by-mfl-ids';
        $res = $this->request($url);
        return $res['data'];
    }

    public function getDraftPickValuesForYear(int $year)
    {
        $url = self::BASE_URL . '/pick-value-by-round/' . $year;
        $res = $this->request($url);
        if (count($res['data']) < 1) {
            return false;
        }
        $ret = [];
        foreach($res['data'] as $round) {
            $roundNumber = $round['round'];
            $ret[$roundNumber] = $round['valueQB1'];
        }
        return $ret;

    }
}
