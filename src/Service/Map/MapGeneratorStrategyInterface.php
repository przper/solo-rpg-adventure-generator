<?php

namespace App\Service\Map;

use App\Enum\DungeonLength;
use App\Enum\MapType;
use App\Interface\MapGeneratorInterface;

interface MapGeneratorStrategyInterface
{
    public function get(MapType $mapType, DungeonLength $length): MapGeneratorInterface;
}
