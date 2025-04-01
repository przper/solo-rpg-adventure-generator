<?php

namespace App\Core\Map;

/**
 * List of all possible types of Tiles. The Map Renderer should support all types below.
 */
enum TileType
{
    case Room;
    case Corridor;
    case Wall;
}
