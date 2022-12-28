<?php

namespace App\Service\RailroadGenerator;

use App\Interface\MapInterface;
use App\Interface\MapGeneratorInterface;

class RailroadMapBuilder implements MapGeneratorInterface
{
    private int $roomsCount;

    private int $minCorridorLength = 1;
    private int $maxCorridorLength = 1;

    public function __construct(
        private RoomGenerator $roomGenerator,
        private CorridorGenerator $corridorGenerator
    ) {
        //
    }

    public function setRoomsCount(int $roomsCount): self
    {
        $this->roomsCount = $roomsCount;

        return $this;
    }

    public function setMinCorridorLength(int $corridorLength): self
    {
        $this->minCorridorLength = $corridorLength;

        return $this;
    }

    public function setMaxCorridorLength(int $corridorLength): self
    {
        $this->maxCorridorLength = $corridorLength;

        return $this;
    }

    public function create(): MapInterface
    {
        $map = new Map();

        /** To Do: Refactor "while (true) ... break;" */
        while (true) {
            $room = $this->roomGenerator->generate();

            $map->addCell($room);

            if (count($map->getRooms()) >= $this->roomsCount) {
                break;
            }

            $this->addCorridorToMap($map);
        }

        return $map;
    }

    private function addCorridorToMap(Map $map): void
    {
        $min = $this->minCorridorLength;
        $max = rand($this->minCorridorLength, $this->maxCorridorLength);

        for($i = $min; $i <= $max + 1; $i++) {
            $corridor = $this->corridorGenerator->generate();

            $map->addCell($corridor);
        }
    }
}
