<?php

namespace App\Service\MapRenderer;

use App\Helper\Coordinates;
use App\Interface\TreasureInterface;
use App\Service\Game\Game;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

class CellWrapper
{
    final public const ROOM_TEMPLATE = 'map_html/_room.html.twig';
    final public const CORRIDOR_TEMPLATE = 'map_html/_corridor.html.twig';
    final public const WALL_TEMPLATE = 'map_html/_wall.html.twig';

    private bool $hasPlayer = false;
    private bool $isKnown = false;
    private bool $wasVisited = false;

    private array $enemies = [];

    public function __construct(
        readonly public TileType $type,
        readonly public Coordinates $coordinates,
    ) {
    }

    public static function fromTile(Tile $tile): self
    {
        return new self(
            type: $tile->type,
            coordinates: $tile->coordinates,
        );
    }

    public function hasPlayer(): bool
    {
        return $this->hasPlayer;
    }

    public function wasVisited(): bool
    {
        return $this->wasVisited;
    }

    public function setWasVisited(bool $wasVisited): self
    {
        $this->wasVisited = $wasVisited;

        return $this;
    }

    public function isKnown(): bool
    {
        return $this->isKnown;
    }

    public function setIsKnown(bool $isKnown): self
    {
        $this->isKnown = $isKnown;

        return $this;
    }

    public function getType(): TileType
    {
        return $this->type;
    }

    public function getTemplate(): string
    {
        if (! $this->isKnown) {
            return self::WALL_TEMPLATE;
        }

        return match ($this->type) {
            TileType::Room => self::ROOM_TEMPLATE,
            TileType::Corridor => self::CORRIDOR_TEMPLATE,
            TileType::Wall => self::WALL_TEMPLATE
        };
    }

    public function getEnemies(): array
    {
        return $this->enemies;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return null;
    }

    public function applyGameState(Game $game): void
    {
        $this->hasPlayer = $this->coordinates->isSame($game->getPlayerPosition()->getCoordinates());

        if ($game->isKnown($this->coordinates)) {
            $this->isKnown = true;
        }

        if ($game->isVisited($this->coordinates)) {
            $this->wasVisited = true;
        }

        $currentEncounter = $game->getEncouter($this->coordinates);
        $this->enemies = $currentEncounter?->getEnemies() ?? [];
    }
}
