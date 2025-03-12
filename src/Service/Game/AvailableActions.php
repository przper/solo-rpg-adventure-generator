<?php

namespace App\Service\Game;

use App\Enum\MovementDirection;

final readonly class AvailableActions
{
    /** @phpstan-param list<value-of<MovementDirection>> $movement */
    public function __construct(
        public array $movement = [],
    ) {
    }
}
