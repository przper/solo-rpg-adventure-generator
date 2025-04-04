<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class BossMonsterEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Boss_Monster;
    }

    public function createEncounter(): Encounter
    {
        return new Encounter(EncounterDifficulty::DEADLY, enemies: [
            new Enemy(10, 10, 'Boss Bebok', DiceStack::fromString('10d6'), 14, ["Greatclub: 1x 1d12+2"]),
        ]);
    }
}
