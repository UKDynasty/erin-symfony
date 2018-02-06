<?php
namespace App\Service;

class Picks
{
    private const SHEET_JSON_URL = 'http://spreadsheets.google.com/feeds/list/1tsYQSMBHSD3nFUQS6urrnqYPJQs94c-IdjJi1CznX1c/4/public/values?alt=json';

    private $rows;

    public function __construct()
    {
        $file= file_get_contents(static::SHEET_JSON_URL);
        $json = json_decode($file, true);
        $this->rows = $json["feed"]["entry"];
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
            if ($row['gsx$owner'] === $franchiseCanonicalName) {
                $picks[] = sprintf("%s%s", $row['gsx$x'], $row['gsx$comments'] ? " " . $row['gsx$comments'] : "");
            }
        }
        return $picks;
    }
}