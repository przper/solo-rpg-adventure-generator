<?php

namespace App\Interface;

use App\Helper\Coordinates;

interface MapCellInterface
{
    public function getCoordinates(): Coordinates;

    public function getType(): string;
}