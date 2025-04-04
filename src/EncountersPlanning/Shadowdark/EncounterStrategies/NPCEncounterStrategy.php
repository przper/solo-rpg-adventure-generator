<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class NPCEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::NPC;
    }

    public function createEncounter(): Encounter
    {
        return new Encounter(EncounterDifficulty::MEDIUM);
    }
}
