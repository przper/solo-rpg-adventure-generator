<?php

namespace App\Interface;

use App\Enum\MovementType;
use App\Helper\Coordinates;

interface MapInterface
{
    /** @return MapCellInterface[][] */
    public function getCells(): array;

    public function getCell(Coordinates $coordinates): ?MapCellInterface;

    public function getMovementType(): MovementType;
}
