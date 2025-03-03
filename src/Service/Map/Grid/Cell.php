<?php

namespace App\Service\Map\Grid;

use App\Helper\Coordinates;
use App\Interface\EnemyInterface;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

abstract class Cell implements MapCellInterface, HasTreasure, HasEnemies
{
    /** @var EnemyInterface[] $enemies */
    protected array $enemies = [];

    protected bool $visited = false;

    public function __construct(
        protected Coordinates $coordinates,
        protected string $type,
        protected ?TreasureInterface $treasure = null,
    ) {
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }

    public function setTreasure(?TreasureInterface $treasure): void
    {
        $this->treasure = $treasure;
    }

    public function getEnemies(): array
    {
        return $this->enemies;
    }

    public function setEnemies(array $enemies): void
    {
        $this->enemies = $enemies;
    }

    public function isVisited(): bool
    {
        return $this->visited;
    }

    public function setVisited(bool $visited): void
    {
        $this->visited = $visited;
    }
}
