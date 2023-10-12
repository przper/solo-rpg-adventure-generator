<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;
use App\Interface\EnemyGeneratorInterface;

class EncounterGenerator
{
    public function __construct(
        private EnemyGeneratorInterface $enemyGenerator
    ) {
        //
    }

    public function create(EncounterDifficulty $difficulty, TeamChallengeRating $teamChallengeRating): Encounter
    {
        /** @var Encounter[] $variants */
        $variants = [];

        for ($i = 0; $i < 50; $i++) {
            $encounter = new Encounter();

            $encounter->setDifficulty($difficulty);
            $encounter->setChallengeRating($teamChallengeRating);

            $enemiesExperienceSum = $teamChallengeRating->getExperienceTresholdForDifficulty($difficulty);
            $encounter->setEnemies(
                $this->enemyGenerator->generateForExperienceNumber($enemiesExperienceSum)
            );

            $variants[] = $encounter;
        }

        usort($variants, function (Encounter $a, Encounter $b) {
            return $a->getAdjustedEnemiesExperienceSum() - $b->getAdjustedEnemiesExperienceSum();
        });

        return $variants[0];
    }
}
