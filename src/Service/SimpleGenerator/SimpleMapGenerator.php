<?php

namespace App\Service\SimpleGenerator;

use App\Service\MapGeneratorInterface;
use App\Service\MapInterface;

class SimpleMapGenerator implements MapGeneratorInterface
{
    public function create(int $rowsCount, int $columnsCount, int $roomsCount): MapInterface
    {
        $map = new Map($rowsCount, $columnsCount, $roomsCount);

        return $map;
    }
}
