<?php

namespace App\Service\Game;

use App\Enum\MovementDirection;

final readonly class Movement
{
    private function __construct(
        public int $deltaX,
        public int $deltaY,
    ) {
    }

    public static function new(): self
    {
        return new self(0, 0);
    }

    public function add(MovementDirection $direction, int $value = 1): self
    {
        $newDeltaX = 0;
        $newDeltaY = 0;

        match ($direction) {
            MovementDirection::West => $newDeltaX -= $value,
            MovementDirection::East => $newDeltaX += $value,
            MovementDirection::North => $newDeltaY -= $value,
            MovementDirection::South => $newDeltaY += $value,
        };

        return new self(
            $this->deltaX + $newDeltaX,
            $this->deltaY + $newDeltaY,
        );
    }
}
