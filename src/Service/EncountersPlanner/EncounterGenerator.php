<?php

namespace App\Service\EncountersPlanner;

use App\Interface\EnemyGeneratorInterface;

class EncounterGenerator
{
    public function __construct(
        private EnemyGeneratorInterface $enemyGenerator
    ) {
        //
    }

    public function create(string $difficulty, TeamChallengeRating $teamChallengeRating): Encounter
    {
        $encounter = new Encounter();

        $encounter->setDifficulty($difficulty);
        $encounter->setChallengeRating($teamChallengeRating);

        $enemiesExperienceSum = $teamChallengeRating->getExperienceTresholdForDifficulty($difficulty);
        $encounter->setEnemies(
            $this->enemyGenerator->generateForExperienceNumber($enemiesExperienceSum)
        );

        return $encounter;
    }
}
