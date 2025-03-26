<?php

namespace App\Service\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Enum\DungeonLength;
use App\Enum\EncounterDifficulty;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\EncountersPlannerInterface;
use App\Service\EncountersPlanner\TeamChallengeRating;

final readonly class EncountersPlanner implements EncountersPlannerInterface
{
    public function __construct(
        private EncounterGenerator $encounterGenerator
    ) {
    }

    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        $encounters = [];

        foreach ($this->generateEncounterDifficultyList($dungeonLength) as $difficulty) {
            $encounters[] = $this->encounterGenerator->create($difficulty, $teamChallengeRating);
        }

        return new EncountersPlan($encounters);
    }

    private function generateEncounterDifficultyList(DungeonLength $dungeonLength): array
    {
        $maxNumberOfEncounters = $dungeonLength->getMaxRoomCount();

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
