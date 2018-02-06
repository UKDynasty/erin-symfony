<?php

namespace App\Service;


use NlpTools\Tokenizers\WhitespaceTokenizer;

class MessageDataExtractor
{
    private const FRANCHISES = [
        [
            "mflFranchiseId" => "0001",
            "espnFranchiseId" => "1",
            "canonical" => "Oxford Seahawks",
            "identifiers" => ["oxford", "seahawks"]
        ],
        [
            "mflFranchiseId" => "0002",
            "espnFranchiseId" => "2",
            "canonical" => "Bradford Championz",
            "identifiers" => ["bradford", "championz"]
        ],
        [
            "mflFranchiseId" => "0003",
            "espnFranchiseId" => "3",
            "canonical" => "Gateshead Spartans",
            "identifiers" => ["gateshead", "spartans"]
        ],
        [
            "mflFranchiseId" => "0004",
            "espnFranchiseId" => "4",
            "canonical" => "Nottingham Bandits",
            "identifiers" => ["nottingham", "bandits", "johnnies", "phoenixes"]
        ],
        [
            "mflFranchiseId" => "0005",
            "espnFranchiseId" => "5",
            "canonical" => "Irish Flyers",
            "identifiers" => ["irish", "flyers"]
        ],
        [
            "mflFranchiseId" => "0006",
            "espnFranchiseId" => "6",
            "canonical" => "Wigan Wombats",
            "identifiers" => ["wigan", "wombats"]
        ],
        [
            "mflFranchiseId" => "0007",
            "espnFranchiseId" => "7",
            "canonical" => "Derby Otters",
            "identifiers" => ["derby", "otters"]
        ],
        [
            "mflFranchiseId" => "0008",
            "espnFranchiseId" => "8",
            "canonical" => "Hereford Chargers",
            "identifiers" => ["hereford", "chargers"]
        ],
        [
            "mflFranchiseId" => "0009",
            "espnFranchiseId" => "9",
            "canonical" => "Coventry Eagles",
            "identifiers" => ["coventry", "eagles"]
        ],
        [
            "mflFranchiseId" => "0010",
            "espnFranchiseId" => "10",
            "canonical" => "Oxford Pythons",
            "identifiers" => ["oxford", "pythons"]
        ],
        [
            "mflFranchiseId" => "0011",
            "espnFranchiseId" => "11",
            "canonical" => "Andover Sandslashers",
            "identifiers" => ["andover", "sandslashers"]
        ],
        [
            "mflFranchiseId" => "0012",
            "espnFranchiseId" => "12",
            "canonical" => "Chippenham Packers",
            "identifiers" => ["chippenham", "packers"]
        ]
    ];

    public function __construct()
    {

    }

    public function extractFranchiseName($message): ?string
    {
        $tokenizer = new WhitespaceTokenizer();
        $tokenizedMessage = $tokenizer->tokenize($message);
        $tokenizedMessage = array_map(function($el) {
            return mb_strtolower($el);
        }, $tokenizedMessage);

        foreach(static::FRANCHISES as $franchise) {
            $overlap = array_intersect($franchise["identifiers"], $tokenizedMessage);
            if (count($overlap) > 0) {
                return $franchise["canonical"];
            }
        }

        return null;
    }

}