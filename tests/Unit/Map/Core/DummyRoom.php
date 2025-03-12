<?php

namespace App\Tests\Unit\Map\Core;

use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileTypes;

final readonly class DummyRoom extends Tile
{
    public function getType(): TileTypes
    {
        return TileTypes::Room;
    }
}
