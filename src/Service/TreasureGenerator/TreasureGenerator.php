<?php

namespace App\Service\TreasureGenerator;

use App\Interface\TreasureGeneratorInterface;
use App\Interface\TreasureInterface;

class TreasureGenerator implements TreasureGeneratorInterface
{
    public function generate(): TreasureInterface
    {
        $treasure = new Treasure();

        $treasure->setValue(rand(100, 200));

        return $treasure;
    }
}