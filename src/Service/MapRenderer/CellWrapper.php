<?php

namespace App\Service\MapRenderer;

use App\Helper\Coordinates;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;
use App\Service\Game\Game;

class CellWrapper
{
    final public const ROOM_TYPE = 'ROOM';
    final public const CORRIDOR_TYPE = 'CORRIDOR';
    final public const WALL_TYPE = 'WALL';

    final public const ROOM_TEMPLATE = 'map/_room.html.twig';
    final public const CORRIDOR_TEMPLATE = 'map/_corridor.html.twig';
    final public const WALL_TEMPLATE = 'map/_wall.html.twig';

    private bool $hasPlayer = false;

    private bool $isVisited = false;

    readonly public string $type;

    readonly public Coordinates $coordinates;

    readonly public ?TreasureInterface $treasure;

    readonly public array $enemies;

    public function __construct(MapCellInterface $baseCell)
    {
        $this->type = $baseCell->getType();
        $this->coordinates = $baseCell->getCoordinates();
        $this->treasure = $baseCell instanceof HasTreasure ? $baseCell->getTreasure() : null;
        $this->enemies = $baseCell instanceof HasEnemies ? $baseCell->getEnemies() : [];
    }

    public function getHasPlayer(): bool
    {
        return $this->hasPlayer;
    }

    public function setHasPlayer(bool $hasPlayer): self
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
            self::ROOM_TYPE => self::ROOM_TEMPLATE,
            self::CORRIDOR_TYPE => self::CORRIDOR_TEMPLATE,
            self::WALL_TYPE => self::WALL_TEMPLATE
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
