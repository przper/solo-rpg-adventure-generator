<?php

namespace App\Service\MapRenderer;

use App\Helper\Coordinates;
use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;
use App\Service\Game\Game;

class CellWrapper
{
    final public const ROOM_TEMPLATE = 'map/_room.html.twig';
    final public const CORRIDOR_TEMPLATE = 'map/_corridor.html.twig';
    final public const WALL_TEMPLATE = 'map/_wall.html.twig';

    private bool $hasPlayer = false;

    private bool $isVisited = false;

    readonly public string $type;

    readonly public Coordinates $coordinates;

    readonly public ?TreasureInterface $treasure;

    public function __construct(MapCellInterface $baseCell)
    {
        $this->type = $baseCell->getType();

        $this->coordinates = $baseCell->getCoordinates();

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
            return self::WALL_TEMPLATE;
        }

        return match ($this->type) {
            'ROOM' => self::ROOM_TEMPLATE,
            'CORRIDOR' => self::CORRIDOR_TEMPLATE,
            'WALL' => self::WALL_TEMPLATE
        };
    }

    public function applyGameState(Game $game): void
    {
        $this->hasPlayer = false;
        $this->isVisited = false;

        $position = $game->getPlayerPosition();

        if ($this->coordinates == $position->getCoordinates()) {
            $this->hasPlayer = true;
        }

        if (in_array($this->coordinates, $game->getVisitedCells())) {
            $this->isVisited = true;
        }
    }
}
