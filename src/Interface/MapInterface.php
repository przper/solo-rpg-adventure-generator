<?php

namespace App\Interface;

use App\Helper\Coordinates;

interface MapInterface
{
    public const MOVEMENT_TYPE_1D = '1d';
    public const MOVEMENT_TYPE_2D = '2d';
    
    /** @return MapCellInterface[][] */
    public function getCells(): array;

    public function getCell(Coordinates $coordinates): ?MapCellInterface;
    
    public function getMovementType(): string;
}
