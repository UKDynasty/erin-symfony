<?php
namespace App\Entity\Interfaces;

interface AssetInterface
{
    public function isListedAsTradeBait() : bool;
    public function isOwned() : bool;
}