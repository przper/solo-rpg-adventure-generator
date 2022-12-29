<?php

namespace App\Service\Map\Railroad;

use App\Interface\MapCellInterface;
use App\Interface\MapInterface;

class Map implements MapInterface
{
    /** @var Cell[][] $cells */
    private array $cells = [];

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getRooms(): array
    {
        $rooms = [];

        array_walk_recursive(
            $this->cells, 
            function (MapCellInterface $cell) use (&$rooms) {
                if ($cell->getType() === Room::getType()) {
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
                if ($cell->getType() === Corridor::getType()) {
                    $corridors[] = $cell;
                }
            }
        );

        return $corridors;
    }

    public function addCell(Cell $cell): self
    {
        $cell->setXCoordinate($this->getLength());
        $cell->setYCoordinate(0);

        $this->cells[] = [$cell];

        return $this;
    }

    public function getLength(): int
    {
        return count($this->cells);
    }
}
