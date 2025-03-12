<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;
use App\Interface\EnemyInterface;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\TreasureInterface;
use App\Service\Map\Core\Tile;
use App\Service\Map\Core\TileType;

final readonly class Corridor extends Tile implements HasEnemies, HasTreasure
{
    /** @param EnemyInterface[] $enemies */
    public function __construct(
        Coordinates $coordinates,
        private ?TreasureInterface $treasure = null,
        private array $enemies = [],
    ) {
        parent::__construct($coordinates);
    }

    public function getType(): TileType
    {
        return TileType::Corridor;
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
