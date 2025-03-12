<?php

namespace App\Service\Game;

use App\Enum\MovementDirection;
use App\Helper\Coordinates;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Map\Core\Map;

class Game
{
    public const STATUS_READY = 'ready';
    public const STATUS_RUNNING = 'running';

    private string $status;

    /** @var Coordinates[] */
    private array $visitedCells = [];

    private PlayerPosition $playerPosition;

    public AvailableActions $actions;

    public function __construct(
        private Map $map,
        private EncountersPlan $encountersPlan,
    ) {
        $this->status = self::STATUS_READY;
        $this->playerPosition = new PlayerPosition(Coordinates::fromIntegers(0, 0));
        $this->actions = $this->checkAvailableActions();
    }

    public function movePlayer(Movement $move): self
    {
        $this->playerPosition->movePlayer($move);

        $newPositionCoordinates = $this->playerPosition->getCoordinates();

        if (! in_array($newPositionCoordinates, $this->visitedCells)) {
            $this->visitedCells[] = clone $newPositionCoordinates;
        }

        $this->actions = $this->checkAvailableActions();

        return $this;
    }

    public function start(): void
    {
        $this->visitedCells[] = clone $this->playerPosition->getCoordinates();
        $this->status = 'running';
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isRunning(): bool
    {
        return $this->status === self::STATUS_RUNNING;
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function getPlayerPosition(): PlayerPosition
    {
        return $this->playerPosition;
    }

    /** @return Coordinates[] */
    public function getVisitedCells(): array
    {
        return $this->visitedCells;
    }

    public function getEncountersPlan(): EncountersPlan
    {
        return $this->encountersPlan;
    }

    public function setEncountersPlan(EncountersPlan $encountersPlan): void
    {
        $this->encountersPlan = $encountersPlan;
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
            if ($nearbyTile->getCoordinates()->getX() < $playerTileCoordinates->getX()) {
                $moves[] = MovementDirection::West;
            }

            if ($nearbyTile->getCoordinates()->getX() > $playerTileCoordinates->getX()) {
                $moves[] = MovementDirection::East;
            }

            if ($nearbyTile->getCoordinates()->getY() < $playerTileCoordinates->getY()) {
                $moves[] = MovementDirection::North;
            }

            if ($nearbyTile->getCoordinates()->getY() > $playerTileCoordinates->getY()) {
                $moves[] = MovementDirection::South;
            }
        }

        return array_map(
            fn(MovementDirection $m) => $m->value,
            $moves,
        );
    }
}
