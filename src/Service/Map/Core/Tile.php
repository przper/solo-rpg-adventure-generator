<?php

namespace App\Service\Map\Core;

use App\Helper\Coordinates;

abstract readonly class Tile
{
    public function __construct(
        private Coordinates $coordinates,
    ) {
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    abstract public function getType(): TileType;
}
