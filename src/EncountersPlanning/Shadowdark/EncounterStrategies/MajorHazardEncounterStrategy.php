<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Obstacle;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class MajorHazardEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Major_Hazard;
    }

    public function createEncounter(): Encounter
    {
        return new Encounter(EncounterDifficulty::HARD, obstacles: [
            new Obstacle('Acid Pool (Major Hazard)', 18),
        ]);
    }
}
