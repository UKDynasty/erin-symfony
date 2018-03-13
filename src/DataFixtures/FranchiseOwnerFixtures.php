<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use App\Entity\Franchise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FranchiseOwnerFixtures extends Fixture
{
    private const FRANCHISES = [
        [
            "ownerName" => "Dan",
            "mflFranchiseId" => "0001",
            "espnFranchiseId" => "1",
            "name" => "Oxford Seahawks",
            "identifiers" => ["oxford", "seahawks"],
            "groupMeUserId" => "36266918",
        ],
        [
            "ownerName" => "Hamza",
            "mflFranchiseId" => "0002",
            "espnFranchiseId" => "2",
            "name" => "Bradford Championz",
            "identifiers" => ["bradford", "championz"]
        ],
        [
            "ownerName" => "Chris",
            "mflFranchiseId" => "0003",
            "espnFranchiseId" => "3",
            "name" => "Gateshead Spartans",
            "identifiers" => ["gateshead", "spartans"]
        ],
        [
            "ownerName" => "Josh",
            "mflFranchiseId" => "0004",
            "espnFranchiseId" => "4",
            "name" => "Nottingham Bandits",
            "identifiers" => ["nottingham", "bandits", "johnnies", "phoenixes"]
        ],
        [
            "ownerName" => "Dermy",
            "mflFranchiseId" => "0005",
            "espnFranchiseId" => "5",
            "name" => "Irish Flyers",
            "identifiers" => ["irish", "flyers"]
        ],
        [
            "ownerName" => "Jonathan",
            "mflFranchiseId" => "0006",
            "espnFranchiseId" => "6",
            "name" => "Wigan Wombats",
            "identifiers" => ["wigan", "wombats"]
        ],
        [
            "ownerName" => "Chris",
            "mflFranchiseId" => "0007",
            "espnFranchiseId" => "7",
            "name" => "Derby Otters",
            "identifiers" => ["derby", "otters"]
        ],
        [
            "ownerName" => "Jason",
            "mflFranchiseId" => "0008",
            "espnFranchiseId" => "8",
            "name" => "Hereford Chargers",
            "identifiers" => ["hereford", "chargers"]
        ],
        [
            "ownerName" => "Phil",
            "mflFranchiseId" => "0009",
            "espnFranchiseId" => "9",
            "name" => "Coventry Eagles",
            "identifiers" => ["coventry", "eagles"]
        ],
        [
            "ownerName" => "Olly",
            "mflFranchiseId" => "0010",
            "espnFranchiseId" => "10",
            "name" => "Oxford Pythons",
            "identifiers" => ["oxford", "pythons"]
        ],
        [
            "ownerName" => "Scott",
            "mflFranchiseId" => "0011",
            "espnFranchiseId" => "11",
            "name" => "Andover Sandslashers",
            "identifiers" => ["andover", "sandslashers"]
        ],
        [
            "ownerName" => "Tom",
            "mflFranchiseId" => "0012",
            "espnFranchiseId" => "12",
            "name" => "Chippenham Packers",
            "identifiers" => ["chippenham", "packers"]
        ]
    ];
    
    public function load(ObjectManager $manager)
    {
        foreach (self::FRANCHISES as $franchise) {
            $ownerEntity = new Owner();
            $ownerEntity->setName($franchise["ownerName"]);
            $ownerEntity->setGroupMeUserId($franchise["groupMeUserId"] ?? null);
            $franchiseEntity = new Franchise();
            $franchiseEntity->setName($franchise["name"]);
            $franchiseEntity->setEspnFranchiseId($franchise["espnFranchiseId"]);
            $franchiseEntity->setMflFranchiseId($franchise["mflFranchiseId"]);
            $franchiseEntity->setIdentifiers($franchise["identifiers"]);
            $manager->persist($ownerEntity);
            $franchiseEntity->setOwner($ownerEntity);
            $manager->persist($franchiseEntity);
        }
        $manager->flush();
    }
}