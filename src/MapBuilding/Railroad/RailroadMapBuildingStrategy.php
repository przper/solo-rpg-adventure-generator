<?php

namespace App\MapBuilding\Railroad;

use App\Core\Enum\DungeonLength;
use App\Core\Map\Map;
use App\Core\Map\MapType;
use App\MapBuilding\MapBuildingStrategy;

final class RailroadMapBuildingStrategy implements MapBuildingStrategy
{
    public function __construct(
        private RailroadMapBuilder $railroadMapBuilder,
    ) {
    }

    public function supports(): MapType
    {
        return MapType::Railroad;
    }

    public function create(DungeonLength $dungeonLength): Map
    {
        return $this
            ->railroadMapBuilder
            ->setMinCorridorLength(2)
            ->setMaxCorridorLength(5)
            ->setMaxRoomsCount(match ($dungeonLength) {
                DungeonLength::SHORT => rand(5, 6),
                DungeonLength::MEDIUM => rand(8, 10),
                DungeonLength::LONG => rand(11, 14),
            })
            ->build();
    }
}
