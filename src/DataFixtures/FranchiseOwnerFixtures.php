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
            'ownerName' => 'Dan',
            'mflFranchiseId' => '0001',
            'name' => 'Oxford Seahawks',
            'identifiers' => ['oxford', 'seahawks'],
            'groupMeUserId' => '36266918',
        ],
        [
            'ownerName' => 'Mike',
            'mflFranchiseId' => '0002',
            'name' => 'Flitwick Fireflies',
            'identifiers' => ['flitwick', 'fireflies'],
            'groupMeUserId' => '37597619',
        ],
        [
            'ownerName' => 'Chris',
            'mflFranchiseId' => '0003',
            'name' => 'Gateshead Spartans',
            'identifiers' => ['gateshead', 'spartans'],
            'groupMeUserId' => '37517186',
        ],
        [
            'ownerName' => 'Josh',
            'mflFranchiseId' => '0004',
            'name' => 'Nottingham Bandits',
            'identifiers' => ['nottingham', 'bandits', 'johnnies', 'phoenixes'],
            'groupMeUserId' => '37521371',
        ],
        [
            'ownerName' => 'Dermy',
            'mflFranchiseId' => '0005',
            'name' => 'Lurgan Leopards',
            'identifiers' => ['lurgan', 'leopards'],
            'groupMeUserId' => '44671697',
        ],
        [
            'ownerName' => 'Jonathan',
            'mflFranchiseId' => '0006',
            'name' => 'Wigan Wombats',
            'identifiers' => ['wigan', 'wombats'],
            'groupMeUserId' => '37592033',
        ],
        [
            'ownerName' => 'Chris',
            'mflFranchiseId' => '0007',
            'name' => 'Derby Otters',
            'identifiers' => ['derby', 'otters'],
            'groupMeUserId' => '37592201',
        ],
        [
            'ownerName' => 'Jason',
            'mflFranchiseId' => '0008',
            'name' => 'Hereford Chargers',
            'identifiers' => ['hereford', 'chargers'],
            'groupMeUserId' => '37392279',
        ],
        [
            'ownerName' => 'Phil',
            'mflFranchiseId' => '0009',
            'name' => 'Coventry Eagles',
            'identifiers' => ['coventry', 'eagles'],
            'groupMeUserId' => '37509201',
        ],
        [
            'ownerName' => 'Olly',
            'mflFranchiseId' => '0010',
            'name' => 'Oxford Pythons',
            'identifiers' => ['oxford', 'pythons'],
            'groupMeUserId' => '37513200',
        ],
        [
            'ownerName' => 'Scott',
            'mflFranchiseId' => '0011',
            'name' => 'Andover Sandslashers',
            'identifiers' => ['andover', 'sandslashers'],
            'groupMeUserId' => '37508673',
        ],
        [
            'ownerName' => 'Tom',
            'mflFranchiseId' => '0012',
            'name' => 'Chippenham Beavers',
            'identifiers' => ['chippenham', 'beavers'],
            'groupMeUserId' => '46481551',
        ]
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::FRANCHISES as $franchise) {
            $ownerEntity = new Owner();
            $ownerEntity->setName($franchise['ownerName']);
            $ownerEntity->setGroupMeUserId($franchise['groupMeUserId'] ?? null);
            $franchiseEntity = new Franchise();
            $franchiseEntity->setName($franchise['name']);
            $franchiseEntity->setMflFranchiseId($franchise['mflFranchiseId']);
            $franchiseEntity->setIdentifiers($franchise['identifiers']);
            $manager->persist($ownerEntity);
            $franchiseEntity->setOwner($ownerEntity);
            $manager->persist($franchiseEntity);
        }
        $manager->flush();
    }
}
