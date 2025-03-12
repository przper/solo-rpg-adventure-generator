<?php

namespace App\Tests\Unit\Map\Core;

use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileTypes;

final readonly class DummyCorridor extends Tile
{
    public function getType(): TileTypes
    {
        return TileTypes::Corridor;
    }
}
