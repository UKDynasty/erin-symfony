<?php

namespace App\Service;


use PHPUnit\Framework\TestCase;

class PicksTest extends TestCase
{


    public function testGetPickReturnsString()
    {
        $picks = new GoogleSheet();

        $res = $picks->getPickOwner("2.02");

        $this->assertInternalType('string', $res, "Got a " . gettype($res) . " instead of a string");

    }

    public function testGetPicksReturnsArray()
    {
        $picks = new GoogleSheet();

        $res = $picks->getPicksList("Oxford Pythons");

        $this->assertInternalType('array', $res, "Got a " . gettype($res) . " instead of an array");
    }
}
