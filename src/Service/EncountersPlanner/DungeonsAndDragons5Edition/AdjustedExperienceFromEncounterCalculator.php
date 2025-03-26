<?php

namespace App\Service\EncountersPlanner\DungeonsAndDragons5Edition;

use App\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\Enemy;

class AdjustedExperienceFromEncounterCalculator
{
    public function getExperienceSum(Encounter $encounter): int
    {
        $rawExperienceSum = array_reduce(
            $encounter->getEnemies(),
            fn ($c, Enemy $e) => $c + $e->getExperiencePoints(),
            0,
        );

        return MultipleEnemiesEncounterExperienceCountModifier::adjustExperiencePoints(
            count($encounter->getEnemies()),
            $rawExperienceSum,
        );
    }
}
