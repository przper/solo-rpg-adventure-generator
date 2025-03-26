<?php

namespace App\Service\Game;

use App\Helper\Coordinates;
use App\Service\Map\Core\Corridor;
use App\Service\Map\Core\Map;

final class FogOfWar implements FogOfWarInterface
{
    /** @var array<string, Coordinates> */
    private array $revealedCoordinates = [];

    /** @var array<string, Coordinates> */
    private array $knownCoordinates = [];

    public function __construct(
        private readonly Map $map,
    ) {
    }

    public function visit(Coordinates $coordinates): void
    {
        if ($this->isVisited($coordinates)) {
            return;
        }

        $this->knownCoordinates[$coordinates->__toString()] = $coordinates;
        $this->revealedCoordinates[$coordinates->__toString()] = $coordinates;

        $nearbyTiles = $this->map->getNearbyTiles($coordinates);
        foreach ($nearbyTiles as $tile) {
            $this->knownCoordinates[$tile->coordinates->__toString()] = $tile->coordinates;
        }

        $currentElement = $this->map->getElementByCoordinates($coordinates);

        // If the current tile is part of a corridor, make all tiles in the corridor known
        if ($currentElement instanceof Corridor) {
            foreach ($currentElement->tiles as $tile) {
                $this->knownCoordinates[(string) $tile->coordinates] = $tile->coordinates;
                
                // Also make adjacent tiles to the corridor known
                $nearbyTiles = $this->map->getNearbyTiles($tile->coordinates);
                foreach ($nearbyTiles as $nearbyTile) {
                    $this->knownCoordinates[(string) $nearbyTile->coordinates] = $nearbyTile->coordinates;
                }
            }
        }
    }

    /**
     * @return Coordinates[]
     */
    public function getRevealedCoordinates(): array
    {
        return array_values($this->revealedCoordinates);
    }

    /**
     * @return Coordinates[]
     */
    public function getKnownCoordinates(): array
    {
        return array_values($this->knownCoordinates);
    }

    public function isVisited(Coordinates $coordinates): bool
    {
        return array_key_exists($coordinates->__toString(), $this->revealedCoordinates);
    }

    public function isKnown(Coordinates $coordinates): bool
    {
        return array_key_exists($coordinates->__toString(), $this->knownCoordinates);
    }
}
