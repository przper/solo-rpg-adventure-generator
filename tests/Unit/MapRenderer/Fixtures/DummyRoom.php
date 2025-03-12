<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

use App\Helper\Coordinates;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

final readonly class DummyRoom extends Tile
{
    public function __construct($x = 0, $y = 0)
    {
        parent::__construct(Coordinates::fromIntegers($x, $y));
    }

    public function getType(): TileType
    {
        return TileType::Room;
    }
}
