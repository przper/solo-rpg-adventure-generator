<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Enum\DungeonLength;
use App\Core\Map\MapType;
use App\MapBuilding\MapGeneratorInterface;
use App\MapBuilding\MapGeneratorStrategyInterface;

class DummyMapGeneratorStrategy implements MapGeneratorStrategyInterface
{
    public function get(MapType $mapType, DungeonLength $length): MapGeneratorInterface
    {
        return new DummyMapGenerator();
    }
}
