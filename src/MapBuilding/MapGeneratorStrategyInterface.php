<?php

namespace App\MapBuilding;

use App\Core\Enum\DungeonLength;
use App\Core\Map\MapType;

interface MapGeneratorStrategyInterface
{
    public function get(MapType $mapType, DungeonLength $length): MapGeneratorInterface;
}
