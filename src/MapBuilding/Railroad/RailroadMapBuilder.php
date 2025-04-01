<?php

namespace App\MapBuilding\Railroad;

use App\Core\Helper\Coordinates;
use App\Core\Map\Map;
use App\MapBuilding\MapGeneratorInterface;

class RailroadMapBuilder implements MapGeneratorInterface
{
    private int $maxRoomsCount = 2;
    private int $minCorridorLength = 1;
    private int $maxCorridorLength = 1;

    /** Internal property to keep track of dungeon length, to properly assign new room's coordinates */
    private int $tileIndex = 0;

    public function __construct(
        private RoomGenerator $roomGenerator,
        private CorridorGenerator $corridorGenerator,
    ) {
    }

    public function setMaxRoomsCount(int $maxRoomsCount): self
    {
        $this->maxRoomsCount = $maxRoomsCount;

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

    public function create(): Map
    {
        $elements = [$this->roomGenerator->starter()];

        $this->tileIndex = 1;
        $roomCount = 1;

        while($roomCount < $this->maxRoomsCount) {
            $corridorLength = rand($this->minCorridorLength, $this->maxCorridorLength);
            $elements[] = $this->corridorGenerator->generate(Coordinates::fromIntegers($this->tileIndex, 0), $corridorLength);
            $this->tileIndex += $corridorLength;
            $elements[] = $this->roomGenerator->generate(Coordinates::fromIntegers($this->tileIndex, 0));
            $roomCount++;
            $this->tileIndex++;
        }

        $map = new Map(
            $this->tileIndex,
            1,
            $elements,
        );

        $this->tileIndex = 0;

        return $map;
    }
}
