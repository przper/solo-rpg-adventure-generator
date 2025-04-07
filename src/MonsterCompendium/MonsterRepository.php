<?php

namespace App\MonsterCompendium;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Entity\Monster;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('monster_compendium.repository')]
interface MonsterRepository
{
    public function supports(): TTRPGSystem;

    /** @return Monster[] */
    public function getMatchingByPhrase(string $phrase): array;
}
