<?php

namespace App\Helper;

class MultipleEnemiesEncounterExperienceCountModifier
{
    private static array $table = [
        //COUNT => MULTIPLIER
        1 => 1.0,
        2 => 1.5,
        3 => 2.0,
        4 => 2.0,
        5 => 2.0,
        6 => 2.0,
        7 => 2.5,
        8 => 2.5,
        9 => 2.5,
        10 => 2.5,
        11 => 3.0,
        12 => 3.0,
        13 => 3.0,
        14 => 3.0,
        15 => 4.0,
    ];

    public static function getMultiplier(int $enemiesCount): float
    {
        if ($enemiesCount < 1) {
            return 0;
        }

        if ($enemiesCount > 15) {
            return 4.0;
        }

        return self::$table[$enemiesCount];
    }

    public static function adjustExperiencePoints(int $enemiesCount, int $experiencePoints): int
    {
        return self::getMultiplier($enemiesCount) * $experiencePoints;
    }
}
