<?php

namespace App\EncountersPlanning;

use App\Core\Enum\DungeonLength;
use App\Core\Enum\TTRPGSystem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('encounters_planning.strategy')]
interface EncountersPlanningStrategy
{
    public function supports(): TTRPGSystem;

    public function plan(DungeonLength $length, TeamChallengeRating $teamLevels): EncountersPlan;
}
