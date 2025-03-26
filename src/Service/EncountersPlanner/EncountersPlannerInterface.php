<?php

namespace App\Service\EncountersPlanner;

use App\Enum\DungeonLength;

interface EncountersPlannerInterface
{
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan;
}
