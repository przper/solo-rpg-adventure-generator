<?php

namespace App\Service\Game;

use App\Helper\Coordinates;

interface FogOfWarInterface
{
    public function visit(Coordinates $coordinates): void;

    public function getRevealedCoordinates(): array;

    public function getKnownCoordinates(): array;

    public function isVisited(Coordinates $coordinates): bool;

    public function isKnown(Coordinates $coordinates): bool;
}
