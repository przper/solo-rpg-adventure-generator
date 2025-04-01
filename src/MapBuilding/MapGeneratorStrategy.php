<?php

namespace App\MapBuilding;

use App\Core\Enum\DungeonLength;
use App\Core\Map\MapType;
use App\MapBuilding\Grid\GridMapBuilder;
use App\MapBuilding\Railroad\RailroadMapBuilder;

class MapGeneratorStrategy implements MapGeneratorStrategyInterface
{
    public function __construct(
        private RailroadMapBuilder $railroadMapBuilder,
        private GridMapBuilder $gridMapBuilder,
    ) {
    }

    public function get(MapType $mapType, DungeonLength $length): MapGeneratorInterface
    {
        return match ($mapType) {
            MapType::Railroad => $this->buildRailroadMap($length),
            MapType::Grid => $this->buildGridMap($length),
        };
    }

    private function buildRailroadMap(DungeonLength $length): MapGeneratorInterface
    {
        return $this
            ->railroadMapBuilder
            ->setMinCorridorLength(2)
            ->setMaxCorridorLength(5)
            ->setMaxRoomsCount(match ($length) {
                DungeonLength::SHORT => rand(5, 6),
                DungeonLength::MEDIUM => rand(8, 10),
                DungeonLength::LONG => rand(11, 14),
            });
    }

    private function buildGridMap(DungeonLength $length): MapGeneratorInterface
    {
        return $this
            ->gridMapBuilder
            ->setRoomSize(1)
            ->setCorridorLength(4)
            ->setGridHeight(match ($length) {
                DungeonLength::SHORT => 2,
                DungeonLength::MEDIUM => 3,
                DungeonLength::LONG => 3,
            })
            ->setGridWidth(match ($length) {
                DungeonLength::SHORT => 3,
                DungeonLength::MEDIUM => 3,
                DungeonLength::LONG => 4,
            })
        ;
    }
}
