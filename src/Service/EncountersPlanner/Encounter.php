<?php

namespace App\Service\EncountersPlanner;

use App\Enum\EncounterDifficulty;

final class Encounter
{
    /** @param Enemy[] $enemies */
    public function __construct(
        private EncounterDifficulty $difficulty,
        private array $enemies = [],
    ) {
    }

    public function getDifficulty(): EncounterDifficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(EncounterDifficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /** @return Enemy[] */
    public function getEnemies(): array
    {
        return $this->enemies;
    }

    /** @param Enemy[] $enemies */
    public function setEnemies(array $enemies): self
    {
        $this->enemies = $enemies;

        return $this;
    }
}
