<?php

namespace App\Service;


use PHPUnit\Framework\TestCase;

class PicksTest extends TestCase
{


    public function testGetPickReturnsString()
    {
        $picks = new Picks();

        $res = $picks->getPickOwner("2.02");

        $this->assertInternalType('string', $res, "Got a " . gettype($res) . " instead of a string");

    }
}
