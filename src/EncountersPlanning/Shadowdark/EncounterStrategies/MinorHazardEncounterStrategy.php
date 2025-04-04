<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Obstacle;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class MinorHazardEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Minor_Hazard;
    }

    public function createEncounter(): Encounter
    {
        $difficulty = [EncounterDifficulty::EASY, EncounterDifficulty::MEDIUM][random_int(0, 1)];

        return new Encounter($difficulty, obstacles: [
            new Obstacle('Rocky Terrain (Minor Hazard)', 12),
        ]);
    }
}
