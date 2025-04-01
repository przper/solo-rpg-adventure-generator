<?php

namespace App\Game;

final readonly class AvailableActions
{
    /** @phpstan-param MovementDirection $movement */
    public function __construct(
        public array $movement = [],
    ) {
    }
}
