<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\Core\Encounter\Enemy;
use App\Core\Helper\DiceStack;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;

class SoloMonsterEncounterStrategy implements EncounterStrategy
{
    public function __construct(
        private TreasureGenerator $treasureGenerator,
    ) {
    }

    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Solo_Monster;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        $difficulty = [EncounterDifficulty::MEDIUM, EncounterDifficulty::HARD][random_int(0, 1)];

        return new Encounter(
            $difficulty,
            enemies: [
                new Enemy(5, 5, 'Bebok Warrior', 7, 13, ["Spear: 2x 1d6"] ),
            ],
            treasures: [
                $this->treasureGenerator->getRandomTreasure($playerLevel->getAveragePlayerLevel()),
            ],
        );
    }
}
