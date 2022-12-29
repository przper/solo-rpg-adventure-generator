<?php

namespace App\Service\Map\Railroad;

use App\Interface\TreasureInterface;

class Room extends Cell
{
    final public const TYPE = 'ROOM';

    private ?TreasureInterface $treasure = null;

    public static function getType(): string
    {
        return static::TYPE;
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
        $room = new self();

        $room->setXCoordinate($x);
        $room->setYCoordinate($y);

        return $room;
    }
}
