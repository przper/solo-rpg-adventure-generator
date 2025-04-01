<?php

namespace App\Game;

use App\Core\Helper\Coordinates;
use App\Core\Map\Map;
use App\Core\Map\Room;

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

        $this->knownCoordinates[(string) $coordinates] = $coordinates;
        $this->revealedCoordinates[(string) $coordinates] = $coordinates;

        $currentElement = $this->map->getElementByCoordinates($coordinates);

        if ($currentElement === null) {
            return;
        }

        foreach ($currentElement->tiles as $tile) {
            $this->knownCoordinates[(string) $tile->coordinates] = $tile->coordinates;
            if ($currentElement instanceof Room) {
                $this->revealedCoordinates[(string) $tile->coordinates] = $tile->coordinates;
            }

            $nearbyTiles = $this->map->getNearbyTiles($tile->coordinates);
            foreach ($nearbyTiles as $nearbyTile) {
                $this->knownCoordinates[(string) $nearbyTile->coordinates] = $nearbyTile->coordinates;
            }
        }
    }

    /**
     * @return Coordinates[]
     */
    public function getVisitedCoordinates(): array
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
        return array_key_exists((string) $coordinates, $this->revealedCoordinates);
    }

    public function isKnown(Coordinates $coordinates): bool
    {
        return array_key_exists((string) $coordinates, $this->knownCoordinates);
    }
}
