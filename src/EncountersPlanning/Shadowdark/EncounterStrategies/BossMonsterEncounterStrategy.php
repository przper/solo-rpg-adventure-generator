<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Encounter\Treasure;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;
use App\EncountersPlanning\TeamChallengeRating;

class BossMonsterEncounterStrategy implements EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Boss_Monster;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        return new Encounter(
            EncounterDifficulty::DEADLY,
            [
                new Enemy(10, 10, 'Boss Bebok', DiceStack::fromString('10d6'), 14, ["Greatclub: 1x 1d12+2"]),
            ],
            treasures: [
                new Treasure('Bag of gold coints (' . random_int(10, 30) . ')'),
            ],
        );
    }
}
