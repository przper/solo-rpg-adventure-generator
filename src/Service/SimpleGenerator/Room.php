<?php

namespace App\Service\SimpleGenerator;

class Room
{
    public function __construct(
        private int $x,
        private int $y
    ) {
        //
    }

    public static function createRandom(int $maxX, int $maxY): self
    {
        $room = new self(rand(0, $maxX), rand(0, $maxY));

        return $room;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
