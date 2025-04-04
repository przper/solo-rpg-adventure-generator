<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Enum\DungeonLength;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlannerInterface;
use App\EncountersPlanning\TeamChallengeRating;

class EncountersPlanner implements EncountersPlannerInterface
{
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        return new EncountersPlan();
    }
}
