<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Enum\DungeonLength;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlannerInterface;
use App\EncountersPlanning\TeamChallengeRating;

final readonly class EncountersPlanner implements EncountersPlannerInterface
{
    public function __construct(
        private EncounterGenerator $encounterGenerator
        private EnemyEncounterGenerator $enemyEncounterGenerator,
    ) {
    }

    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        $encounters = [];

        foreach ($this->generateEncounterDifficultyList($dungeonLength) as $difficulty) {
            $encounters[] = $this->encounterGenerator->create($difficulty, $teamChallengeRating);
                $encounters[] = $this->enemyEncounterGenerator->create($difficulty, $teamChallengeRating);
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
