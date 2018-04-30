<?php
namespace App\Service;



class HumanReadableHelpers
{
    public function playersToList(array $players)
    {
        return implode(
            "\n",
            array_map(
                function($player) {
                    return sprintf("%s, %s", $player->getName(), $player->getPosition()->getName());
                },
                $players
            )
        );
    }

    public function roundOfPicksToList(array $picks)
    {
        return implode(
            "\n",
            array_map(
                function($pick) {
                    return $pick->getPickTextIncludingOwnerAndOriginalOwner();
                },
                $picks
            )
        );
    }

    public function ordinal(int $number)
    {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) {
            return $number . 'th';
        }
        return $number . $ends[$number % 10];
    }
}