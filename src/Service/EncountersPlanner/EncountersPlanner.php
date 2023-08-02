<?php

namespace App\Service\EncountersPlanner;

class EncountersPlanner
{
    final public const DUNGEON_SIZE_SHORT = 'DUNGEON_SHORT';
    final public const DUNGEON_SIZE_MEDIUM = 'DUNGEON_MEDIUM';
    final public const DUNGEON_SIZE_LONG = 'DUNGEON_LONG';

    public function __construct(
        private EncounterGenerator $encounterGenerator
    ) {
        //
    }

    /**
     * @param string $dungeonLength
     * @param TeamChallengeRating $teamChallengeRating
     *
     * @return Encounter[]
     */
    public function plan(string $dungeonLength, TeamChallengeRating $teamChallengeRating): array
    {
        $maxNumberOfEncounters = match ($dungeonLength) {
            self::DUNGEON_SIZE_SHORT => rand(5, 6),
            self::DUNGEON_SIZE_MEDIUM => rand (11, 12),
            self::DUNGEON_SIZE_LONG => rand(17, 18)
        };
        // dump($maxNumberOfEncounters);

        $mediumEncountersCount = ceil($maxNumberOfEncounters / 2);
        $easyEncountersCount = floor(($maxNumberOfEncounters - $mediumEncountersCount) / 2);
        $hardEncounterCount = $maxNumberOfEncounters - $mediumEncountersCount - $easyEncountersCount;
        $deadlyEncounterCount = 0;

        if ($hardEncounterCount > 1 && rand(1, 100) < 60) {
            $hardEncounterCount -= 2;
            $deadlyEncounterCount++;
        }

        $encounterPlan =  [
            'easy' => $easyEncountersCount,
            'medium' => $mediumEncountersCount,
            'hard' => $hardEncounterCount,
            'deadly' => $deadlyEncounterCount
        ];

        // dump($encounterPlan);
        $encounters = [];

        foreach($encounterPlan as $difficulty => $count) {
            for($i = 0; $i < $count; $i++) {
                $encounters[] = $this->encounterGenerator->create($difficulty, $teamChallengeRating);
            }
        }

        return $encounters;
    }
}
