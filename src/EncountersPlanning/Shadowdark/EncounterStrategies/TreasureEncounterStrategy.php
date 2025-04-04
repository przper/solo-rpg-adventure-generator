<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Obstacle;
use App\Core\Encounter\Treasure;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class TreasureEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Treasure;
    }

    public function createEncounter(): Encounter
    {
        $difficulty = [EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD][random_int(0, 1)];

        return new Encounter($difficulty,
            obstacles: [new Obstacle('Treasure Chest with Poison Gas Trap', 18, 12)],
            treasures: [new Treasure("Gems (" . DiceStack::fromString('6d10')->roll() . " gp)")],
        ) ;
    }
}
