<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\EnemyInterface;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\TreasureInterface;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileTypes;

final readonly class Room extends Tile implements HasEnemies, HasTreasure
{
    /** @param EnemyInterface[] $enemies */
    public function __construct(
        Coordinates $coordinates,
        private ?TreasureInterface $treasure = null,
        private array $enemies = [],
    ) {
        parent::__construct($coordinates);
    }

    public function getType(): TileTypes
    {
        return TileTypes::Room;
    }

    public function getEnemies(): array
    {
        return $this->enemies;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }
}
