<?php

namespace App\EncountersPlanning\DungeonsAndDragons5Edition;

use App\EncountersPlanning\Encounter;
use App\EncountersPlanning\EncounterDifficulty;
use App\EncountersPlanning\Obstacle;
use App\EncountersPlanning\TeamChallengeRating;

final class ObstacleEncounterGenerator
{
    public function create(EncounterDifficulty $difficulty, TeamChallengeRating $teamChallengeRating): Encounter
    {
        return new Encounter($difficulty, obstacles: [
            new Obstacle('Spike Trap', 12),
        ]);
    }
}
