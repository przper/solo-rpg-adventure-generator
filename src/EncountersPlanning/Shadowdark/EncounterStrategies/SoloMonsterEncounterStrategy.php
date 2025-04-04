<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class SoloMonsterEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Solo_Monster;
    }

    public function createEncounter(): Encounter
    {
        $difficulty = [EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD][random_int(0, 1)];

        return new Encounter($difficulty, enemies: [
            new Enemy(5, 5, 'Bebok Warrior', DiceStack::fromString('5d6'), 13, ["Spear: 2x 1d6"] ),
        ]);
    }
}
