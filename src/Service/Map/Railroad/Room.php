<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\HasTreasure;
use App\Interface\TreasureInterface;

class Room extends Cell implements HasTreasure
{
    final public const TYPE = 'ROOM';

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

    public static function fromCoordinates(Coordinates $coordinates): self
    {
        $room = new self();

        $room->setCoordinates($coordinates);

        return $room;
    }
}
