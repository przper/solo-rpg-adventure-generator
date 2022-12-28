<?php

namespace App\Service\Map\Roguelike;

use App\Interface\RoomInterface;
use App\Interface\TreasureInterface;

class Room implements RoomInterface
{
    private ?TreasureInterface $treasure = null;

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

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }
}
