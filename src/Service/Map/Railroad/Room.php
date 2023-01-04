<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;

class Room extends Cell
{
    final public const TYPE = 'ROOM';

    public function getType(): string
    {
        return self::TYPE;
    }

    public static function fromCoordinates(Coordinates $coordinates): self
    {
        $room = new self();

        $room->setCoordinates($coordinates);

        return $room;
    }
}
