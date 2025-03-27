<?php

namespace App\Tests\Fixtures\Dummies;

use App\Enum\DungeonLength;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\EncountersPlanner\EncountersPlannerInterface;
use App\Service\EncountersPlanner\TeamChallengeRating;

class EmptyEncounterPlanner implements EncountersPlannerInterface
{
    public function plan(DungeonLength $dungeonLength, TeamChallengeRating $teamChallengeRating): EncountersPlan
    {
        return new EncountersPlan();
    }
}
