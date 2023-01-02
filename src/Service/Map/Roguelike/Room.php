<?php

namespace App\Service\Map\Roguelike;

use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

class Room implements MapCellInterface
{
    final public const TYPE = 'ROOM';

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

    public function getXCoordinate(): int
    {
        return $this->x;
    }

    public function getYCoordinate(): int
    {
        return $this->y;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }
}
