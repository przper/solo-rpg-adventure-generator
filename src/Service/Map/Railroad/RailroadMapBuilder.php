<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\MapGeneratorInterface;
use App\Service\Map\Core\Map;

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
        $tiles[] = $this->roomGenerator->starter();

        $this->tileIndex = 1;
        $roomCount = 1;

        while($roomCount < $this->maxRoomsCount) {
            array_push($tiles, ...$this->generateCorridorSet());

            $tiles[] = $this->roomGenerator->generate(Coordinates::fromIntegers($this->tileIndex, 0));
            $roomCount++;
            $this->tileIndex++;
        }
        $map = new Map(
            $this->tileIndex,
            1,
            $tiles,
        );

        $this->tileIndex = 0;

        return $map;
    }

    /** @return Corridor[] */
    private function generateCorridorSet(): array
    {
        $min = $this->minCorridorLength;
        $max = rand($this->minCorridorLength, $this->maxCorridorLength);

        $corridors = [];

        for ($i = $min; $i <= $max + 1; $i++) {
            $corridor = $this->corridorGenerator->generate(Coordinates::fromIntegers($this->tileIndex, 0));

            $corridors[] = $corridor;
            $this->tileIndex++;
        }

        return $corridors;
    }
}
