<?php

namespace App\EncountersPlanning;

use App\Core\Enum\DungeonLength;

interface EncountersPlannerInterface
{
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan;
}
