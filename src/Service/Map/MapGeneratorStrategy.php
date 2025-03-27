<?php

namespace App\Service\Map;

use App\Enum\DungeonLength;
use App\Enum\MapType;
use App\Interface\MapGeneratorInterface;
use App\Service\Map\Grid\GridMapBuilder;
use App\Service\Map\Railroad\RailroadMapBuilder;

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
            ->setMaxRoomsCount($length->getMaxRoomCount());
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
                DungeonLength::LONG => 4,
            })
            ->setGridWidth(match ($length) {
                DungeonLength::SHORT => 3,
                DungeonLength::MEDIUM => 4,
                DungeonLength::LONG => 5,
            })
        ;
    }
}
