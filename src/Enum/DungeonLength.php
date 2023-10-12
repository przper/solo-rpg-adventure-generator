<?php

namespace App\Enum;

enum DungeonLength: string
{
    case SHORT = 'short';
    case MEDIUM = 'medium';
    case LONG = 'long';

    public function getMaxRoomCount(): int
    {
        return match ($this) {
            self::SHORT => 6,
            self::MEDIUM => 12,
            self::LONG => 18,
        };
    }
}
