<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;

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

        return [
            EncounterDifficulty::EASY->value => $easyEncountersCount,
            EncounterDifficulty::MEDIUM->value => $mediumEncountersCount,
            EncounterDifficulty::HARD->value => $hardEncounterCount,
            EncounterDifficulty::DEADLY->value => $deadlyEncounterCount
        ];

        $encounters = [];

        foreach($encounterPlan as $difficulty => $count) {
            for($i = 0; $i < $count; $i++) {
                $encounters[] = $this->pickEasiestEncounter($difficulty, $teamChallengeRating);
            }
        }

        return $encounters;
    }

    private function pickEasiestEncounter($difficulty, $teamChallengeRating): Encounter
    {
        /** @var Encounter[] $variants */
        $variants = [];

        for ($i = 0; $i < 10; $i++) {
            $variants[] = $this->encounterGenerator->create($difficulty, $teamChallengeRating);
        }

        usort($variants, function (Encounter $a, Encounter $b) {
            return $a->getAdjustedEnemiesExperienceSum() - $b->getAdjustedEnemiesExperienceSum();
        });

        return $variants[0];
    }
}
