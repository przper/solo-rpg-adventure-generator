<?php

namespace App\EncountersPlanning\Shadowdark;

use App\EncountersPlanning\Encounter;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('encounters_planning.shadowdark.encounter')]
interface EncounterStrategy
{
    public function getDungeonRoomType(): DungeonRoomType;
    
    public function createEncounter(): Encounter;
}
