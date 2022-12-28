<?php

namespace App\Service\SimpleGenerator;

use App\Interface\MapInterface;


class Map implements MapInterface
{
    private array $cells;

    /** @var Room[] $rooms */
    private array $rooms;

    public function __construct(
        private int $rowsCount,
        private int $columnsCount
    ) {
        $this->cells = array_pad([], $rowsCount, array_pad([], $columnsCount, 0));
        $this->rooms = [];

        while (count($this->rooms) < 15) {
            $room = Room::createRandom($rowsCount - 1, $columnsCount - 1);
            dump($room);

            if (in_array($room, $this->rooms)) {
                continue;
            }

            $this->rooms[] = $room;
            $this->cells[$room->getY()][$room->getX()] = 1;
        }
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
