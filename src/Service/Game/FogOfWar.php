<?php

namespace App\Service\Game;

use App\Helper\Coordinates;
use App\Service\Map\Core\Map;

final class FogOfWar
{
    /** @var array<string, Coordinates> */
    private array $revealedTiles = [];

    /** @var array<string, Coordinates> */
    private array $knownTiles = [];

    public function __construct(
        private Map $map
    ) {
    }

    public function visit(Coordinates $coordinates): void
    {
        // Mark the current tile as revealed
        $this->revealedTiles[$coordinates->__toString()] = $coordinates;
        
        // Current tile is also known
        $this->knownTiles[$coordinates->__toString()] = $coordinates;

        // Mark nearby tiles as known
        $nearbyTiles = $this->map->getNearbyTiles($coordinates);
        foreach ($nearbyTiles as $tile) {
            $nearbyCoordinates = $tile->getCoordinates();
            $this->knownTiles[$nearbyCoordinates->__toString()] = $nearbyCoordinates;
        }
    }

    /**
     * @return Coordinates[]
     */
    public function getRevealedTiles(): array
    {
        return array_values($this->revealedTiles);
    }

    /**
     * @return Coordinates[]
     */
    public function getKnownTiles(): array
    {
        return array_values($this->knownTiles);
    }
    
    public function isVisited(Coordinates $coordinates): bool
    {
        return array_key_exists($coordinates->__toString(), $this->revealedTiles);
    }
    
    public function isKnown(Coordinates $coordinates): bool
    {
        return array_key_exists($coordinates->__toString(), $this->knownTiles);
    }
}
