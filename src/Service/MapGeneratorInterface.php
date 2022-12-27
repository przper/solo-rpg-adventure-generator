<?php

namespace App\Service;

interface MapGeneratorInterface
{
    public function create(int $rowsCount, int $columnsCount, int $roomsCount): MapInterface;
}
