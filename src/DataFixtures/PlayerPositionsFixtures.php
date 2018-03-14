<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerPositionsFixtures extends Fixture
{
    private const POSITIONS = [
        ["QB", 4],
        ["RB", 3],
        ["WR", 2],
        ["TE", 1],
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::POSITIONS as $position) {
            $positionEntity = new Position();
            $positionEntity->setName($position[0]);
            $positionEntity->setPriority($position[1]);
            $manager->persist($positionEntity);
        }
        $manager->flush();
    }
}