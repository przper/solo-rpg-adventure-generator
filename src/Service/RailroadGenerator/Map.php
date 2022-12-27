<?php

namespace App\Service\RailroadGenerator;

use App\Service\MapInterface;

class Map implements MapInterface
{
    private array $cells;

    /** @var Room[] $rooms */
    private array $rooms;

    public function __construct(
        private int $rowsCount,
        private int $roomsCount,
    ) {
        $this->cells = array_pad([], $rowsCount, 0);
        $this->rooms = [];

        while (count($this->rooms) < $this->roomsCount) {
            $room = Room::createRandom($rowsCount - 1);
            dump($room);

            if (in_array($room, $this->rooms)) {
                continue;
            }

            $this->rooms[] = $room;
            $this->cells[$room->getY()] = 1;
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
}
