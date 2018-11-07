<?php
namespace App\Service;

class GoogleSheet
{
    private const SHEET_TAXI_SQUADS_URL = 'http://spreadsheets.google.com/feeds/list/1tsYQSMBHSD3nFUQS6urrnqYPJQs94c-IdjJi1CznX1c/9/public/values?alt=json';

    private const SHEET_JSON_URL = 'http://spreadsheets.google.com/feeds/list/1tsYQSMBHSD3nFUQS6urrnqYPJQs94c-IdjJi1CznX1c/3/public/values?alt=json';

    private $rows;

    public function __construct()
    {
//        $file= file_get_contents(static::SHEET_JSON_URL);
//        $json = json_decode($file, true);
//        $this->rows = $json["feed"]["entry"];
    }

    public function getPickOwner($pick)
    {
        if (!isset($this->rows[$pick-1])) {
            return false;
        }

        return $this->rows[$pick-1]['gsx$owner']['$t'];
    }

    /**
     * @param $franchiseCanonicalName string
     * @return array
     */
    public function getPicksList($franchiseCanonicalName)
    {
        $picks = [];
        foreach ($this->rows as $row) {
            if (trim($row['gsx$owner']['$t']) === $franchiseCanonicalName) {
                $row['gsx$x']['$t'];
                $picks[] = sprintf("%s%s", $row['gsx$x']['$t'], $row['gsx$comments']['$t'] ? " " . $row['gsx$comments']['$t'] : "");
            }
        }
        return $picks;
    }

    public function getTaxiSquadsCounts()
    {
        $file= file_get_contents(static::SHEET_TAXI_SQUADS_URL);
        $json = json_decode($file, true);
        $rows = $json["feed"]["entry"];
        $counts = array_filter($rows[5], function($k) { return strpos($k, 'gsx$') === 0; }, ARRAY_FILTER_USE_KEY);#

        $parsedCounts = [];

        foreach($counts as $k => $v) {
            $parsedCounts[substr($k, 4)] = (int)$v['$t'];
        }

        return $parsedCounts;
    }
}