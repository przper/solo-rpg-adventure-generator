<?php

namespace App\Service\RailroadGenerator;

class Room
{
    public function __construct(
        private int $y
    ) {
        //
    }

    public static function createRandom(int $maxY): self
    {
        $room = new self(rand(0, $maxY));

        return $room;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
