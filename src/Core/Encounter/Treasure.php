<?php

namespace App\Core\Encounter;

final class Treasure
{
    private bool $pickedUp = false;

    public function __construct(
        public readonly string $name,
    ) {
    }

    public function isPickedUp(): bool
    {
        return $this->pickedUp;
    }

    public function pickUp(): void
    {
        $this->pickedUp = true;
    }
}
