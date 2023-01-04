<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

abstract class Cell implements MapCellInterface, HasTreasure, HasEnemies
{
    private Coordinates $coordinates;

    private ?TreasureInterface $treasure = null;

    /** @var Enemy[] $enemies */
    private array $enemies = [];

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(Coordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    abstract public function getType(): string;

    public function getTreasure(): ?TreasureInterface
    {
        return $this->treasure;
    }

    public function setTreasure(TreasureInterface $treasure): self
    {
        $this->treasure = $treasure;

        return $this;
    }

    public function getEnemies(): array
    {
        return $this->enemies;
    }

    /**
     * @param EnemyInterface[] $enemies
     * 
     * @return self
     */
    public function setEnemies(array $enemies): self
    {
        $this->enemies = $enemies;

        return $this;
    }
}