<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Helper\MultipleEnemiesEncounterExperienceCountModifier;
use App\EncountersPlanning\Encounter;
use App\EncountersPlanning\Enemy;

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
