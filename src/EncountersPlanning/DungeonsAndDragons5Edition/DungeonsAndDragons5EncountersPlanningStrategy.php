<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\Core\Enum\DungeonLength;
use App\Core\Enum\TTRPGSystem;
use App\Core\Encounter\EncounterDifficulty;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;
use App\EncountersPlanning\TTRPGSystem;

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

    public function plan(DungeonLength $length, TeamChallengeRating $teamLevels): EncountersPlan
    {
        return $this
            ->encountersPlanner
            ->plan($length, $teamLevels);
    }
}
