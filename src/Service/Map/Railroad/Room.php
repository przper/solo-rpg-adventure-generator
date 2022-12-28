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

    public static function getTemplate(): string
    {
        return 'map-generator/_room.html.twig';
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

    public static function fromX(int $x): self
    {
        $room = new self();

        $room->setX($x);

        return $room;
    }
}
