<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Obstacle;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class TrapEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Trap;
    }

    public function createEncounter(): Encounter
    {
        $difficulty = [EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD][random_int(0, 1)];

        return new Encounter($difficulty, obstacles: [
            new Obstacle('Spike Trap', 18, 12),
        ]);
    }
}
