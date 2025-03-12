<?php

namespace App\Service\Map\Core;

/**
 * List of all possible types of Tiles. The Map Renderer should support all types below.
 */
enum TileTypes
{
    case Room;
    case Corridor;
    case Wall;
}
