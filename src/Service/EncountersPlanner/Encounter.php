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

    /** @return Enemy[] */
    public function getEnemies(): array
    {
        return array_filter($this->enemies, fn(Enemy $e) => $e->isAlive());
    }

    /** @return Enemy[] */
    public function getAllEnemies(): array
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
