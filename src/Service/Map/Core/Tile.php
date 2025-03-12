<?php

namespace App\Service\Map\Core;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;

abstract readonly class Tile implements MapCellInterface
{
    public function __construct(
        private Coordinates $coordinates,
    ) {
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    abstract public function getType(): TileTypes;
}
