<?php

namespace App\EncountersPlanning;

final class Obstacle
{
    private bool $isRemoved = false;

    public function __construct(
        public string $name,
        public readonly int $dcToRemove,
        public readonly int $dcToSpot = 0,
    ) {
    }

    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }

    public function remove(): void
    {
        $this->isRemoved = true;
    }
}
