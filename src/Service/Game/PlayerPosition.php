<?php

namespace App\Service\Game;

use App\Interface\RoomInterface;

class PlayerPosition
{
    private int $xCoordinate = 0;
    private int $yCoordinate = 0;

    public function getX(): int
    {
        return $this->xCoordinate;
    }

    public function getY(): int
    {
        return $this->yCoordinate;
    }

    public function move(int $x, int $y): self
    {
        $this->xCoordinate += $x;
        $this->yCoordinate += $y;

        return $this;
    }

    public static function fromRoom(RoomInterface $room): self
    {
        $position = new self();

        return $position;
    }
}