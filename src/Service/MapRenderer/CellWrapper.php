<?php

namespace App\Service\MapRenderer;

use App\Helper\Coordinates;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\TreasureInterface;
use App\Service\Game\Game;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

class CellWrapper
{
    final public const ROOM_TEMPLATE = 'map/_room.html.twig';
    final public const CORRIDOR_TEMPLATE = 'map/_corridor.html.twig';
    final public const WALL_TEMPLATE = 'map/_wall.html.twig';

    private bool $hasPlayer = false;
    private bool $isKnown = false;
    private bool $wasVisited = false;

    public function __construct(
        readonly public TileType $type,
        readonly public Coordinates $coordinates,
        readonly public ?TreasureInterface $treasure= null,
        readonly public array $enemies = [],
    ) {
    }

    public static function fromTile(Tile $tile): self
    {
        return new self(
            type: $tile->getType(),
            coordinates: $tile->getCoordinates(),
            treasure: $tile instanceof HasTreasure ? $tile->getTreasure() : null,
            enemies: $tile instanceof HasEnemies ? $tile->getEnemies() : [],
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

    public function getType(): TileType
    {
        return $this->type;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
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

    public function applyGameState(Game $game): void
    {
        $this->hasPlayer = $this->coordinates->isSame($game->getPlayerPosition()->getCoordinates());

        if ($game->isKnown($this->coordinates)) {
            $this->isKnown = true;
        }

        if ($game->isVisited($this->coordinates)) {
            $this->wasVisited = true;
        }
    }
}
