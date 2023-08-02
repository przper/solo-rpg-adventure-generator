<?php

namespace App\Service\EncounterPlanner;

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

        $enemiesExperienceSum = $teamChallengeRating->getExperienceTresholdForDifficulty($difficulty);
        $enemies = $this->enemyGenerator->generateForExperienceNumber($enemiesExperienceSum);

        $encounter->setDifficulty($difficulty);
        $encounter->setChallengeRating($teamChallengeRating);
        $encounter->setEnemies($enemies);

        return $encounter;
    }
}