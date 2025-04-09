<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Encounter\Treasure;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;

class MonsterMobEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Monster_Mob;
    }

    public function createEncounter(): Encounter
    {
        $difficulty = [EncounterDifficulty::EASY, EncounterDifficulty::MEDIUM][random_int(0, 1)];

        return new Encounter(
            $difficulty,
            [
                new Enemy(1, 1, 'Bebok', DiceStack::fromString('1d6'), 11, ["Spear: 1x 1d6"]),
                new Enemy(1, 1, 'Bebok', DiceStack::fromString('1d6'), 11, ["Spear: 1x 1d6"]),
                new Enemy(1, 1, 'Bebok', DiceStack::fromString('1d6'), 11, ["Spear: 1x 1d6"]),
            ],
            treasures: [
                new Treasure('Bag of gold coints (' . random_int(5, 10) . ')'),
            ],
        );
    }
}
