<?php

namespace App\Interface;

use App\Helper\Coordinates;

interface MapInterface
{
    /** @return MapCellInterface[][] */
    public function getCells(): array;

    public function getCell(Coordinates $coordinates): ?MapCellInterface;
}
