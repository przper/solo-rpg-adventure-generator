<?php

namespace App\Service\Game;

use App\Enum\MovementType;
use App\Helper\Coordinates;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Map\Core\Map;
use App\Service\Map\Core\Tile;

class Game
{
    public const STATUS_READY = 'ready';
    public const STATUS_RUNNING = 'running';

    private string $status;

    /** @var Coordinates[] */
    private array $visitedCells = [];

    private PlayerPosition $playerPosition;


    public function __construct(
        private Map $map,
        private EncountersPlan $encountersPlan,
    ) {
        $this->status = self::STATUS_READY;
        $this->playerPosition = new PlayerPosition(Coordinates::fromIntegers(0, 0));
    }

    public function movePlayerByIntegers(int $deltaX, int $deltaY): self
    {
        $this->playerPosition->moveBy($deltaX, $deltaY);

        $newPositionCoordinates = $this->playerPosition->getCoordinates();

        if (! in_array($newPositionCoordinates, $this->visitedCells)) {
            $this->visitedCells[] = clone $newPositionCoordinates;
        }

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

    public function setMap(Map $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getPlayerPosition(): PlayerPosition
    {
        return $this->playerPosition;
    }

    public function setPlayerPosition(PlayerPosition $position): self
    {
        if (! $this->isRunning()) {
            throw new \Exception('Game is not running!');
        }

        $this->playerPosition = $position;
        $this->visitedCells[] = clone $position->getCoordinates();

        return $this;
    }

    /** @return Tile[] */
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

    public function getMovementType(): MovementType
    {
        return $this->map->movementType;
    }
}
