<?php

namespace App\EncountersPlanning;

final class Encounter
{
    /** @param Enemy[] $enemies */
    public function __construct(
        private EncounterDifficulty $difficulty,
        private array $enemies = [],
    ) {
    }

    public function resolve(string $result): void
    {
        if ($result === 'all_slain') {
            foreach ($this->enemies as $enemy) {
                $enemy->slay();
            }
        }
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
}
