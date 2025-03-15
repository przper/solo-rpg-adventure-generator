<?php

namespace App\Service\Map\Core;

use App\Helper\Coordinates;

final readonly class Tile
{
    public function __construct(
        public Coordinates $coordinates,
        public TileType $type, // determine what should be visible on map
    ) {
    }
}
