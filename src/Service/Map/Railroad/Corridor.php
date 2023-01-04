<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\HasEnemies;
use App\Interface\HasTreasure;
use App\Interface\EnemyInterface;
use App\Interface\TreasureInterface;

class Corridor extends Cell implements HasTreasure, HasEnemies
{
    final public const TYPE = 'CORRIDOR';

    private ?TreasureInterface $treasure = null;

    /** @var Enemy[] $enemies */
    private array $enemies = [];

    public function getType(): string
    {
        return self::TYPE;
    }

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

    public static function fromCoordinates(Coordinates $coordinates): self
    {
        $corridor = new self();

        $corridor->setCoordinates($coordinates);

        return $corridor;
    }
}
