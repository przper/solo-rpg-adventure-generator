<?php

namespace App\Service\Map\Roguelike;

use App\Interface\MapInterface;

class Map implements MapInterface
{
    private array $cells = [];

    /** @var Room[] $rooms */
    private array $rooms;

    public function __construct(
        private int $rowsCount,
        private int $columnsCount,
        private int $roomsCount
    ) {
        // $this->cells = array_pad([], $rowsCount, array_pad([], $columnsCount, new Wall()));

        foreach (range(0, $columnsCount - 1) as $columnIndex) {
            foreach (range(0, $rowsCount - 1) as $rowIndex) {
                $this->cells[$rowIndex][$columnIndex] = new Wall($columnIndex, $rowIndex);
            }
        }

        $this->rooms = [];

        while (count($this->rooms) < $this->roomsCount) {
            $room = Room::createRandom($rowsCount - 1, $columnsCount - 1);
            // dump($room);

            if (in_array($room, $this->rooms)) {
                continue;
            }

            $this->rooms[] = $room;
            $this->cells[$room->getYCoordinate()][$room->getXCoordinate()] = $room;
        }

        dump($this);
    }

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function getRowsCount(): int
    {
        return $this->rowsCount;
    }

    public function getColumnsCount(): int
    {
        return $this->columnsCount;
    }
}
