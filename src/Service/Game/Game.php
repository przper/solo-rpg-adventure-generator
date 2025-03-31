<?php

namespace App\Service\Game;

use App\Enum\MovementDirection;
use App\Helper\Coordinates;
use App\Service\EncountersPlanner\Encounter;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Map\Core\Map;

class Game
{
    private PlayerPosition $playerPosition;
    private AvailableActions $actions;
    private FogOfWar $fogOfWar;
    private Encounters $encounters;

    public function __construct(
        private Map $map,
        EncountersPlan $encountersPlan = new EncountersPlan(),
    ) {
        $this->playerPosition = new PlayerPosition(Coordinates::fromIntegers(0, 0));
        $this->fogOfWar = new FogOfWar($map);
        $this->encounters = new Encounters($map, $encountersPlan);
        $this->actions = $this->checkAvailableActions();
    }

    public function movePlayer(Movement $move): self
    {
        $this->playerPosition->movePlayer($move);
        $this->fogOfWar->visit($this->playerPosition->getCoordinates());

        $this->actions = $this->checkAvailableActions();

        return $this;
    }

    public function start(): void
    {
        $this->fogOfWar->visit($this->playerPosition->getCoordinates());
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function getEncouter(Coordinates $coordinates): ?Encounter
    {
        return $this->encounters->getEncounter($coordinates);
    }

    public function getCurrentEncounter(): ?Encounter
    {
        return $this->encounters->getEncounter($this->playerPosition->getCoordinates());
    }

    public function getPlayerPosition(): PlayerPosition
    {
        return $this->playerPosition;
    }

    public function isVisited(Coordinates $coordinates): bool
    {
        return $this->fogOfWar->isVisited($coordinates);
    }

    public function isKnown(Coordinates $coordinates): bool
    {
        return $this->fogOfWar->isKnown($coordinates);
    }

    public function getAvailableActions(): AvailableActions
    {
        return $this->actions;
    }

    private function checkAvailableActions(): AvailableActions
    {
        $movement = $this->getAvailableMoves();

        return new AvailableActions(
            movement: $movement,
        );
    }

    /**
     * @return string[]
     */
    private function getAvailableMoves(): array
    {
        $playerTileCoordinates = $this->playerPosition->getCoordinates();
        $moves = [];

        $nearbyTiles = $this->map->getNearbyTiles($playerTileCoordinates);
        foreach ($nearbyTiles as $nearbyTile) {
            if ($nearbyTile->coordinates->x < $playerTileCoordinates->x) {
                $moves[] = MovementDirection::West;
            }

            if ($nearbyTile->coordinates->x > $playerTileCoordinates->x) {
                $moves[] = MovementDirection::East;
            }

            if ($nearbyTile->coordinates->y < $playerTileCoordinates->y) {
                $moves[] = MovementDirection::North;
            }

            if ($nearbyTile->coordinates->y > $playerTileCoordinates->y) {
                $moves[] = MovementDirection::South;
            }
        }

        return array_map(
            fn(MovementDirection $m) => $m->value,
            $moves,
        );
    }

    public function resolveCurrentEncounter(?string $result): void
    {
        $this->encounters->resolve($this->playerPosition->getCoordinates(), $result);
    }
}
