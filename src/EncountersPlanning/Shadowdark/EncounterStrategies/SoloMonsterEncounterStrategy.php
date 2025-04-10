<?php

namespace App\EncountersPlanning\Shadowdark\EncounterStrategies;

use App\Core\Encounter\Encounter;
use App\Core\Encounter\EncounterDifficulty;
use App\EncountersPlanning\Shadowdark\DungeonRoomType;
use App\EncountersPlanning\Shadowdark\EncounterStrategy;
use App\EncountersPlanning\Shadowdark\TreasureGenerator;
use App\EncountersPlanning\TeamChallengeRating;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\ShadowdarkMonsterRepository;

class SoloMonsterEncounterStrategy implements EncounterStrategy
{
    public function __construct(
        private ShadowdarkMonsterRepository $monsterRepository,
        private TreasureGenerator $treasureGenerator,
    ) {
    }

    public function getDungeonRoomType(): DungeonRoomType
    {
        return DungeonRoomType::Solo_Monster;
    }

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter
    {
        $enemy = $this->generateMonster($playerLevel)->toEnemy();

        return new Encounter(
            EncounterDifficulty::MEDIUM,
            [$enemy],
            treasures: [
                $this->treasureGenerator->getRandomTreasure($playerLevel->getAveragePlayerLevel()),
            ],
        );
    }

    private function generateMonster(TeamChallengeRating $playerLevels): Monster
    {
        /** 1:1 MONSTERS table from Shadowdark core rules, page 193 */
        $monsterLevelTable = [
            0 => 1,
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 3,
            5 => 3,
            6 => 3,
            7 => 5,
            8 => 5,
            9 => 5,
            10 => 7,
        ];
        $monsterLevels = array_map(fn(int $i) => $monsterLevelTable[$i], $playerLevels->toArray());
        sort($monsterLevels);
        $maxMonsterLevel = $monsterLevels[count($monsterLevels) - 1];

        if ($maxMonsterLevel < $playerLevels->getAveragePlayerLevel()) {
            $monsters = $this->monsterRepository->get(
                minChallengeRating: $maxMonsterLevel,
                maxChallengeRating: $playerLevels->getAveragePlayerLevel(),
            );
        } else {
            $monsters = $this->monsterRepository->get(
                minChallengeRating: $playerLevels->getAveragePlayerLevel(),
                maxChallengeRating: $maxMonsterLevel,
            );
        }

        return $monsters[array_rand($monsters)];
    }
}
