<?php

namespace App\Service\Map\Roguelike;

use App\Helper\Coordinates;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

final readonly class Wall extends Tile
{
    public function __construct(Coordinates $coordinates)
    {
        parent::__construct($coordinates);
    }

    public function getType(): TileType
    {
        return TileType::Wall;
    }
}
