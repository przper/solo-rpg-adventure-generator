<?php

namespace App\MapBuilding\Grid;

use App\Core\Enum\DungeonLength;
use App\Core\Map\Map;
use App\Core\Map\MapType;
use App\MapBuilding\MapBuildingStrategy;

final class GridMapBuildingStrategy implements MapBuildingStrategy
{
    public function __construct(
        private GridMapBuilder $gridMapBuilder,
    ) {
    }

    public function supports(): MapType
    {
        return MapType::Grid;
    }

    public function create(DungeonLength $dungeonLength): Map
    {
        return $this
            ->gridMapBuilder
            ->setRoomSize(1)
            ->setCorridorLength(4)
            ->setGridHeight(match ($dungeonLength) {
                DungeonLength::SHORT => 2,
                DungeonLength::MEDIUM => 3,
                DungeonLength::LONG => 3,
            })
            ->setGridWidth(match ($dungeonLength) {
                DungeonLength::SHORT => 3,
                DungeonLength::MEDIUM => 3,
                DungeonLength::LONG => 4,
            })
            ->build();
    }
}
