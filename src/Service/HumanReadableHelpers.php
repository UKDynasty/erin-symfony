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
}