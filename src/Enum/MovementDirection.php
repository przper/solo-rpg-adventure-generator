<?php

namespace App\Enum;

/**
 * List of all possible moves in the Game. It depends on Map's MovementType.
 */
enum MovementDirection: string
{
    // One Dimension
    case Forward = 'forward'; // +x, 0
    case Backward = 'backward'; // -x, 0

    // Two Dimension
    case West = 'west'; // -x, 0
    case East = 'east'; // +x, 0
    case North = 'north'; // -y
    case South = 'south'; // +y

    /** @return self[] */
    public static function getPossibleMovesByMapMovementType(MovementType $type): array
    {
        return match ($type) {
            MovementType::OneDimension => [self::Forward, self::Backward],
            MovementType::TwoDimension => [self::West, self::East, self::North, self::South],
        };
    }
}
