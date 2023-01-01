<?php

namespace App\Service\Game;

use App\Interface\MapCellInterface;
use App\Interface\MapInterface;
use App\Service\Game\Exception\NoGeneratedMapException;
use App\Service\Game\Exception\UnknownPlayerPositionException;

class Game
{
    private MapInterface $map;

    private PlayerPosition $playerPosition;

    private array $visitedCells = [];

    public function getMap(): MapInterface
    {
        return $this->map;
    }

    public function setMap(MapInterface $map): self
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
        $this->playerPosition = $position;
        $this->visitedCells[] = ['x' => $position->getX(), 'y' => $position->getY()];

        return $this;
    }

    /** @return MapCellInterface[] */
    public function getVisitedCells(): array
    {
        return $this->visitedCells;
    }

    public function movePlayer(int $x, int $y): self
    {
        $this->playerPosition->move($x, $y);

        $newPositionCoordinates = [
            'x' => $this->playerPosition->getX(), 
            'y' => $this->playerPosition->getY()
        ];

        if (! in_array($newPositionCoordinates, $this->visitedCells)) {
            $this->visitedCells[] = $newPositionCoordinates;
        }

        return $this;
    }

    public function start(): void
    {
        if (! $this->map) {
            throw new NoGeneratedMapException();
        }

        if (! $this->playerPosition) {
            throw new UnknownPlayerPositionException();
        }

        //
    }
}
