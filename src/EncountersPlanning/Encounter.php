<?php

namespace App\EncountersPlanning;

final class Encounter
{
    private bool $isResolved = false;

    /**
     * @param Enemy[] $enemies
     * @param Obstacle[] $obstacles
     * @param Treasure[] $treasures
     */
    public function __construct(
        private EncounterDifficulty $difficulty,
        private array $enemies = [],
        private array $obstacles = [], //e.g. traps, blockages
        private array $treasures = [],
    ) {
    }

    public function resolve(string $result): void
    {
        if ($result === 'all_slain') {
            foreach ($this->enemies as $enemy) {
                $enemy->slay();
            }
            $this->isResolved = true;
        }

        if ($result === 'obstacle_removed') {
            foreach ($this->obstacles as $obstacle) {
                $obstacle->remove();
            }
            $this->isResolved = true;
        }

        if (str_contains($result, 'treasure_picked_up')) {
            [$result, $index] = explode(':', $result);
            if (array_key_exists($index, $this->treasures)) {
                $this->treasures[$index]->pickUp();
            }
        }
    }

    public function isResolved(): bool
    {
        return $this->isResolved;
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

    /** @return Treasure[] */
    public function getTreasures(): array
    {
        return array_filter($this->treasures, fn(Treasure $t) => !$t->isPickedUp());
    }
}
