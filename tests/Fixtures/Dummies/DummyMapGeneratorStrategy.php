<?php

namespace App\Tests\Fixtures\Dummies;

use App\Enum\DungeonLength;
use App\Enum\MapType;
use App\Interface\MapGeneratorInterface;
use App\Service\Map\MapGeneratorStrategyInterface;

class DummyMapGeneratorStrategy implements MapGeneratorStrategyInterface
{
    public function get(MapType $mapType, DungeonLength $length): MapGeneratorInterface
    {
        return new DummyMapGenerator();
    }
}
