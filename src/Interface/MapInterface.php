<?php

namespace App\Interface;

interface MapInterface
{
    /** @return MapCellInterface[][] */
    public function getCells(): array;
}
