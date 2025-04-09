<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Encounter\Encounter;
use App\EncountersPlanning\TeamChallengeRating;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('encounters_planning.shadowdark.encounter')]
interface EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType;

    public function createEncounter(TeamChallengeRating $playerLevel): Encounter;
}
