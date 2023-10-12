<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;

class EncountersPlan
{
    /** @var Encounter[] */
    private array $encounters;

    /**
     * @param Encounter[] $encounters
     */
    public function __construct(
        array $encounters = [],
    ) {
        foreach ($encounters as $encounter) {
            if (!$encounter instanceof Encounter) {
                throw new \UnexpectedValueException();
            }
        }

        $this->encounters = $encounters;
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

    public function addEncounter(Encounter $encounter): void
    {
        $this->encounters[] = $encounter;
    }
}
