<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Helper\DiceStack;

enum DungeonRoomType
{
    case Empty;
    case Trap;
    case Minor_Hazard;
    case Solo_Monster;
    case NPC;
    case Monster_Mob;
    case Major_Hazard;
    case Treasure;
    case Boss_Monster;

    public static function rollRoomType(): self
    {
        return match (DiceStack::fromString('1d10')->roll()) {
            1, 2 => self::Empty,
            3 => self::Trap,
            4 => self::Minor_Hazard,
            5 => self::Solo_Monster,
            6 => self::NPC,
            7 => self::Monster_Mob,
            8 => self::Major_Hazard,
            9 => self::Treasure,
            10 => self::Boss_Monster,
        };
    }
}
