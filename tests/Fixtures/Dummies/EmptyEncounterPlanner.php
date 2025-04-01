<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Enum\DungeonLength;
use App\EncountersPlanning\EncountersPlan;
use App\EncountersPlanning\EncountersPlannerInterface;
use App\EncountersPlanning\TeamChallengeRating;

class EmptyEncounterPlanner implements EncountersPlannerInterface
{
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        return new EncountersPlan();
    }
}
