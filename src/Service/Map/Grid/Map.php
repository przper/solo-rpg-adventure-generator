<?php

namespace App\Service\Map\Grid;

use App\Enum\MovementType;
use App\Helper\Coordinates;
use App\Interface\MapCellInterface;
use App\Interface\MapInterface;

class Map implements MapInterface
{
    /** @var MapCellInterface[][] */
    private array $cells = [];

    public function addCell(MapCellInterface $cell): void
    {
        $coordinates = $cell->getCoordinates();
        $this->cells[$coordinates->getY()][$coordinates->getX()] = $cell;
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getCell(Coordinates $coordinates): ?MapCellInterface
    {
        return $this->cells[$coordinates->getY()][$coordinates->getX()] ?? null;
    }

    public function getRooms(): array
    {
        $rooms = [];

        array_walk_recursive(
            $this->cells,
            function (MapCellInterface $cell) use (&$rooms) {
                if ($cell->getType() === Room::TYPE) {
                    $rooms[] = $cell;
                }
            }
        );

        return $rooms;
    }

    public function getCorridors(): array
    {
        $corridors = [];

        array_walk_recursive(
            $this->cells,
            function (MapCellInterface $cell) use (&$corridors) {
                if ($cell->getType() === Corridor::TYPE) {
                    $corridors[] = $cell;
                }
            }
        );

        return $corridors;
    }

    public function getMovementType(): MovementType
    {
        return MovementType::TwoDimension;
    }

    public function dumpRaw(): void
    {
    }
}
