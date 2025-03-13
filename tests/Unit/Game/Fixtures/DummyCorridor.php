<?php

namespace App\Tests\Unit\Game\Fixtures;

use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

final readonly class DummyCorridor extends Tile
{
    public function getType(): TileType
    {
        return TileType::Corridor;
    }
}
