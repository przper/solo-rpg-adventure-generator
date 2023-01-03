<?php

namespace App\Service\Map\Roguelike;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;

class Wall implements MapCellInterface
{
    final public const TYPE = 'WALL';

    public function __construct(
        private Coordinates $coordinates
    ) {
        //
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
