<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;

final readonly class EncountersPlan
{
    /**
     * @param Encounter[] $encounters
     */
    public function __construct(
        public array $encounters = [],
    ) {
    }

    public function getEncountersByDifficulty(EncounterDifficulty $difficulty): array
    {
        return array_keys(array_filter($this->encounters, fn (Encounter $e) => $e->getDifficulty() === $difficulty));
    }
}
