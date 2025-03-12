<?php

namespace App\Enum;

/**
 * List of all possible moves in the Game
 */
enum MovementDirection: string
{
    case West = 'west'; // -x, 0
    case East = 'east'; // +x, 0
    case North = 'north'; // 0, -y
    case South = 'south'; // 0, +y
}
