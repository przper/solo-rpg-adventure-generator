<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Obstacle;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;

class TreasureEncounterStrategy implements EncounterStrategy
{
    public function __construct(
        private TreasureGenerator $treasureGenerator,
    ) {
    }

    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Treasure;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        $difficulty = [EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD][random_int(0, 1)];

        return new Encounter($difficulty,
            obstacles: [new Obstacle('Treasure Chest with Poison Gas Trap', 18, 12)],
            treasures: [
                $this->treasureGenerator->getRandomTreasure($playerLevel->getAveragePlayerLevel()),
            ],
        ) ;
    }
}
