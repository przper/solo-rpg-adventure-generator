<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Enum\DungeonLength;
use App\Core\Enum\TTRPGSystem;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;

class DungeonsAndDragons5EncountersPlanningStrategy implements EncountersPlanningStrategy
{
    public function __construct(
        private EncountersPlanner $encountersPlanner,
    ) {
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::DungeonAndDragons5Edition;
    }

    public function plan(DungeonLength $length, TeamChallengeRating $fromLevelsAsIntegers): EncountersPlan
    {
        return $this
            ->encountersPlanner
            ->plan($length, $fromLevelsAsIntegers);
    }
}
