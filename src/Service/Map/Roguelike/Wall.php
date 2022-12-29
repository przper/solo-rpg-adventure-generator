<?php

namespace App\Service\Map\Roguelike;

use App\Interface\MapCellInterface;

class Wall implements MapCellInterface
{
    final public const TYPE = 'WALL';

    public function __construct(
        private int $x,
        private int $y
    ) {
        //
    }

    public static function createFromCoordinates(int $x, int $y): self
    {
        $wall = new self($x, $y);

        return $wall;
    }

    public function getXCoordinate(): int
    {
        return $this->x;
    }

    public function getYCoordinate(): int
    {
        return $this->y;
    }

    public static function getType(): string
    {
        return static::TYPE;
    }
}
