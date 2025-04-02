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
        $type = rand(1, 100) < 50 ? 'Trap' : 'Blockade';

        return new Encounter($difficulty, obstacles: [
            new Obstacle(
                name: $type === 'Trap' ? 'Spike Trap' : 'Rocks blockade',
                dcToRemove: $type === 'Trap'
                    ? match ($difficulty) {
                        EncounterDifficulty::EASY => 9,
                        EncounterDifficulty::MEDIUM => 12,
                        EncounterDifficulty::HARD => 15,
                        EncounterDifficulty::DEADLY => 18,
                    } : 12,
                dcToSpot: $type === 'Trap' ? 12 : 0,
            ),
        ]);
    }
}
