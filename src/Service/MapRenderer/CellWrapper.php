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

    private bool $isVisited = false;

    private array $connections = [];

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

    /**
     * Gets the cell's connections
     *
     * @return array<string, string> Map of directions to connection types
     */
    public function getConnections(): array
    {
        return $this->connections;
    }

    /**
     * Check if the cell has a connection in a specific direction
     */
    public function hasConnection(string $direction): bool
    {
        return isset($this->connections[$direction]);
    }

    /**
     * Get the connection type in a specific direction
     */
    public function getConnectionType(string $direction): ?string
    {
        return $this->connections[$direction] ?? null;
    }

    public function getTemplate(): string
    {
        if (! $this->isVisited) {
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
