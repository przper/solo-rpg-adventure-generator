<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Encounter\EncounterDifficulty;
use App\Core\Map\DungeonLength;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;
use App\EncountersPlanning\TTRPGSystem;

final class DungeonsAndDragons5EncountersPlanningStrategy implements EncountersPlanningStrategy
{
    public function __construct(
        private EnemyEncounterGenerator $enemyEncounterGenerator,
        private ObstacleEncounterGenerator $obstacleEncounterGenerator,
    ) {
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::DungeonAndDragons5Edition;
    }

    public function plan(DungeonLength $length, TeamChallengeRating $teamLevels): EncountersPlan
    {
        $encounters = [];

        foreach ($this->generateEncounterDifficultyList($length) as $difficulty) {
            if (in_array($difficulty, [EncounterDifficulty::EASY, EncounterDifficulty::MEDIUM]) && rand(1, 100) < 33) {
                $encounters[] = $this->obstacleEncounterGenerator->create($difficulty, $teamLevels);
            } else {
                $encounters[] = $this->enemyEncounterGenerator->create($difficulty, $teamLevels);
            }
        }

        return new EncountersPlan($encounters);
    }

    /** @return EncounterDifficulty[] */
    private function generateEncounterDifficultyList(DungeonLength $dungeonLength): array
    {
        /**
         * Short is 1 day of traveling (0 long rest)
         * Medium is 2 day of traveling (1 long rest)
         * Long is 3 day of traveling (2 long rest)
         *
         * Approximately 6 encounters should happen in an adventuring day
         */
        $maxNumberOfEncounters = match($dungeonLength) {
            DungeonLength::SHORT => 6,
            DungeonLength::MEDIUM => 12,
            DungeonLength::LONG => 18,
        };

        $mediumEncountersCount = ceil($maxNumberOfEncounters / 2);
        $easyEncountersCount = floor(($maxNumberOfEncounters - $mediumEncountersCount) / 2);
        $hardEncounterCount = $maxNumberOfEncounters - $mediumEncountersCount - $easyEncountersCount;
        $deadlyEncounterCount = 0;

        if ($hardEncounterCount > 1 && rand(1, 100) < 60) {
            $hardEncounterCount -= 2;
            $deadlyEncounterCount++;
        }

        return array_merge(
            array_fill(0, $mediumEncountersCount, EncounterDifficulty::MEDIUM),
            array_fill(0, $easyEncountersCount, EncounterDifficulty::EASY),
            array_fill(0, $hardEncounterCount, EncounterDifficulty::HARD),
            array_fill(0, $deadlyEncounterCount, EncounterDifficulty::DEADLY)
        );
    }
}
