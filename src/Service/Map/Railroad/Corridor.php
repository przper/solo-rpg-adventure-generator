<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;

class Corridor extends Cell
{
    final public const TYPE = 'CORRIDOR';

    public function getType(): string
    {
        return self::TYPE;
    }

    public static function fromCoordinates(Coordinates $coordinates): self
    {
        $corridor = new self();

        $corridor->setCoordinates($coordinates);

        return $corridor;
    }
}
