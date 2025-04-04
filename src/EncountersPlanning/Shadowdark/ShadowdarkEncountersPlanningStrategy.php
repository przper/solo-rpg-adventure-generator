<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Enum\DungeonLength;
use App\Core\Enum\TTRPGSystem;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlanningStrategy;
use App\EncountersPlanning\TeamChallengeRating;

class ShadowdarkEncountersPlanningStrategy implements EncountersPlanningStrategy
{
    public function __construct(
        private EncountersPlanner $encountersPlanner,
    ) {
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::Shadowdark;
    }

    public function plan(DungeonLength $length, TeamChallengeRating $teamLevels): EncountersPlan
    {
        return $this->encountersPlanner->plan($length, $teamLevels);
    }
}
