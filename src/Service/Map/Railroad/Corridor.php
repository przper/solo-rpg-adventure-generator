<?php

namespace App\Service\Map\Railroad;

use App\Interface\HasTreasure;
use App\Interface\TreasureInterface;

class Corridor extends Cell implements HasTreasure
{
    final public const TYPE = 'CORRIDOR';

    private ?TreasureInterface $treasure = null;

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

    public static function fromCoordinates(int $x, int $y = 0): self
    {
        $corridor = new self();

        $corridor->setXCoordinate($x);
        $corridor->setYCoordinate($y);

        return $corridor;
    }
}
