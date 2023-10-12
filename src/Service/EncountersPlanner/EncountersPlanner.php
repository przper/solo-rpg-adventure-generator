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
        $encountersPlan = $this->generateEncounterNumberPerDifficultMap($dungeonLength);

        $encounters = [];

        foreach($encountersPlan as $difficulty => $count) {
            for($i = 0; $i < $count; $i++) {
                $encounters[] = $this->encounterGenerator->create(EncounterDifficulty::from($difficulty), $teamChallengeRating);
            }
        }
        shuffle($encounters);

        return new EncountersPlan($encounters);
    }

    private function generateEncounterNumberPerDifficultMap(DungeonLength $dungeonLength): array
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

        return [
            EncounterDifficulty::EASY->value => $easyEncountersCount,
            EncounterDifficulty::MEDIUM->value => $mediumEncountersCount,
            EncounterDifficulty::HARD->value => $hardEncounterCount,
            EncounterDifficulty::DEADLY->value => $deadlyEncounterCount
        ];
    }
}
