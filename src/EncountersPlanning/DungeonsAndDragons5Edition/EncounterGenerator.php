<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\EncountersPlanning\Encounter;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\Enemy;
use App\EncountersPlanning\TeamChallengeRating;

class EncounterGenerator
{
    public function __construct(
        private EnemyGenerator $enemyGenerator,
        private AdjustedExperienceFromEncounterCalculator $experienceCalculator,
    ) {
    }

    public function create(EncounterDifficulty $difficulty, TeamChallengeRating $teamChallengeRating): Encounter
    {
        /** @var Encounter[] $variants */
        $variants = [];

        for ($i = 0; $i < 50; $i++) {
            $enemies = $this->generateEnemiesFittingDifficultyExperienceTreshold($difficulty, $teamChallengeRating);

            $variants[] = new Encounter($difficulty, $enemies);
        }

        usort($variants, function (Encounter $a, Encounter $b) {
            return $this->experienceCalculator->getExperienceSum($a) - $this->experienceCalculator->getExperienceSum($b);
        });

        return $variants[0];
    }

    /** @return Enemy[] */
    private function generateEnemiesFittingDifficultyExperienceTreshold(EncounterDifficulty $difficulty, TeamChallengeRating $teamChallengeRating): array
    {
        $enemies = [];
        $experienceSum = 0;
        $adjustedExperience = 0;
        $expectedExperienceSum = $this->getExperienceTresholdForDifficulty($difficulty, $teamChallengeRating);

        while($adjustedExperience < $expectedExperienceSum) {
            /**
             * ToDo:
             * Program encounter "type" - boss lair, patrol, barracks, ambush, single monster, etc.
             */
            $enemy = $this->enemyGenerator->generate();

            $enemies[] = $enemy;
            $experienceSum += $enemy->getExperiencePoints();
            $adjustedExperience = MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(count($enemies), $experienceSum);
        }

        return $enemies;
    }

    private function getExperienceTresholdForDifficulty(EncounterDifficulty $difficulty, TeamChallengeRating $teamChallengeRating): int
    {
        $experienceTreshold = 0;

        foreach ($teamChallengeRating as $level) {
            $experienceTreshold += $this->getPlayerExperienceTreshold($level, $difficulty);
        }

        return $experienceTreshold;
    }

    private function getPlayerExperienceTreshold(int $playerLevel, EncounterDifficulty $difficulty): int
    {
        $map = [
            //CHARACTER_LEVEL => [EASY, MEDIUM, HARD, DEADLY]
            1 => ['easy' => 25, 'medium' => 50, 'hard' =>  75, 'deadly' => 100],
            2 => ['easy' => 50, 'medium' => 100, 'hard' =>  150, 'deadly' => 200],
            3 => ['easy' => 75, 'medium' => 150, 'hard' =>  225, 'deadly' => 400],
            4 => ['easy' => 125, 'medium' => 250, 'hard' =>  375, 'deadly' => 500],
            //TO DO: fill the rest
        ];

        return $map[$playerLevel][$difficulty->value];
    }
}
