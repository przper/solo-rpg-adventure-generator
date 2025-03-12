<?php

namespace App\Tests\Unit\Map\Core\Fixtures;

use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

final readonly class DummyRoom extends Tile
{
    public function getType(): TileType
    {
        return TileType::Room;
    }
}
