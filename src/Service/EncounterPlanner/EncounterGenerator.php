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

    public function create(string $difficulty, TeamChallangeRating $teamChallangeRating): Encounter
    {
        $encounter = new Encounter();

        $enemiesExperienceSum = $teamChallangeRating->getExperienceTresholdForDifficulty($difficulty);
        $enemies = $this->enemyGenerator->generateForExperienceNumber($enemiesExperienceSum);

        $encounter->setDifficulty($difficulty);
        $encounter->setChallangeRating($teamChallangeRating);
        $encounter->setEnemies($enemies);

        return $encounter;
    }
}