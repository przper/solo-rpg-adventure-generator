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

    public function count(): int
    {
        return count($this->encounters);
    }

    public function easyDifficultyCount(): int
    {
        $encounters = array_filter($this->encounters, fn (Encounter $e) => $e->getDifficulty() === EncounterDifficulty::EASY);

        return count($encounters);
    }

    public function mediumDifficultyCount(): int
    {
        $encounters = array_filter($this->encounters, fn (Encounter $e) => $e->getDifficulty() === EncounterDifficulty::MEDIUM);

        return count($encounters);
    }

    public function hardDifficultyCount(): int
    {
        $encounters = array_filter($this->encounters, fn (Encounter $e) => $e->getDifficulty() === EncounterDifficulty::HARD);

        return count($encounters);
    }

    public function deadlyDifficultyCount(): int
    {
        $encounters = array_filter($this->encounters, fn (Encounter $e) => $e->getDifficulty() === EncounterDifficulty::DEADLY);

        return count($encounters);
    }
}
