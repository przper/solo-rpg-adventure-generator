<?php

namespace App\Service\EncountersPlanner;

use App\Enum\DungeonLength;
use App\Enum\EncounterDifficulty;

class EncountersPlanner
{
    public function __construct(
        private readonly EncounterGenerator $encounterGenerator
    ) {
        //
    }

    /**
     * @param DungeonLength $dungeonLength
     * @param TeamChallengeRating $teamChallengeRating
     *
     * @return EncountersPlan
     */
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        $plan = new EncountersPlan();

        foreach ($this->generateEncounterDifficultyList($dungeonLength) as $difficulty) {
            $encounter = $this->encounterGenerator->create($difficulty, $teamChallengeRating);

            $plan->addEncounter($encounter);
        }

        return $plan;
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
