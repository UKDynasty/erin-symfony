<?php
namespace App\Service;



use App\Entity\Player;

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

    /**
     * @param array|Player[] $players
     * @return string
     */
    public function playersToPositionSeparatedList(array $players)
    {
        $positions = [];

        foreach($players as $player) {
            $positions[$player->getPosition()->getName()][] = $player;
        }

        $parts = [];

        foreach($positions as $position => $positionPlayers) {
            $parts[] = sprintf(
                '%s: %s',
                $position,
                implode(
                    ', ',
                    array_map(
                        function(Player $player) {
                            return $player->getLastName();
                        },
                        $positionPlayers
                    )
                )
            );
        }



        return implode(
            "\n",
            $parts
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