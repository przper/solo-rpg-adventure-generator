<?php

namespace App\Game;

use App\Core\Helper\Coordinates;

/**
 * Handles the Fog of War mechanics, storing information about
 * visited and known coordinates in the map. Allows tracking
 * of visited and nearby tiles to progressively reveal the map.
 *
 * Rules:
 * - Entering a single tile of a Room marks it as visited in fully (MVP)
 * - Entering a single tile of Corridor makes it known whole,
 *   but reveals only the current tile (reason: it is not lit at all, only our light source)
 */
interface FogOfWarInterface
{
    public function visit(Coordinates $coordinates): void;

    public function getVisitedCoordinates(): array;

    public function getKnownCoordinates(): array;

    public function isVisited(Coordinates $coordinates): bool;

    public function isKnown(Coordinates $coordinates): bool;
}
