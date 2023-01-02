<?php

namespace App\Service\MapRenderer;

use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;
use App\Service\Game\Game;

class CellWrapper
{
    private bool $hasPlayer = false;

    private bool $isVisited = false;

    public readonly string $type;

    public readonly int $x;

    public readonly int $y;

    public readonly ?TreasureInterface $treasure;

    public function __construct(MapCellInterface $baseCell)
    {
        $this->type = $baseCell->getType();

        $this->x = $baseCell->getXCoordinate();
        $this->y = $baseCell->getYCoordinate();

        $this->treasure = $baseCell instanceof HasTreasure ? $baseCell->getTreasure() : null;
    }

    public function getHasPlayer(): bool
    {
        return $this->hasPlayer;
    }

    public function setHasPlayer(int $hasPlayer): self
    {
        $this->hasPlayer = $hasPlayer;

        return $this;
    }

    public function getIsVisited(): bool
    {
        return $this->isVisited;
    }

    public function setIsVisited(bool $isVisited): self
    {
        $this->isVisited = $isVisited;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }

    public function getTemplate(): string
    {
        if (! $this->isVisited) {
            return 'map/_wall.html.twig';
        }

        return match ($this->type) {
            'ROOM' => 'map/_room.html.twig',
            'CORRIDOR' => 'map/_corridor.html.twig',
            'WALL' => 'map/_wall.html.twig'
        };
    }

    public function applyGameState(Game $game): void
    {
        $position = $game->getPlayerPosition();

        if ($this->x === $position->getX() && $this->y === $position->getY()) {
            $this->hasPlayer = true;
        }

        $cellCoordinates = ['x'=> $this->x, 'y' => $this->y];

        if (in_array($cellCoordinates, $game->getVisitedCells())) {
            $this->isVisited = true;
        }
    }
}
