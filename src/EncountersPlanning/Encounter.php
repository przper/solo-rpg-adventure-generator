<?php

namespace App\EncountersPlanning;

final class Encounter
{
    public bool $isResolved = false;

    /**
     * @param Enemy[] $enemies
     * @param Obstacle[] $obstacles
     */
    public function __construct(
        private EncounterDifficulty $difficulty,
        private array $enemies = [],
        private array $obstacles = [], //e.g. traps, blockages
    ) {
    }

    public function resolve(string $result): void
    {
        if ($result === 'all_slain') {
            foreach ($this->enemies as $enemy) {
                $enemy->slay();
            }
        }

        if ($result === 'obstacle_removed') {
            foreach ($this->obstacles as $obstacle) {
                $obstacle->remove();
            }
        }

        $this->isResolved = true;
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

    /** @return Obstacle[] */
    public function getObstacles(): array
    {
        return $this->obstacles;
    }
}
