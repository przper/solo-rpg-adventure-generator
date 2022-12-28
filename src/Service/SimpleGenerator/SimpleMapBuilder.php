<?php

namespace App\Service\SimpleGenerator;

use App\Interface\MapInterface;
use App\Interface\MapGeneratorInterface;

class SimpleMapBuilder implements MapGeneratorInterface
{
    private int $rowsCount;
    private int $columnsCount;

    private int $roomsCount;

    public function create(): MapInterface
    {
        $map = new Map($this->rowsCount, $this->columnsCount, $this->roomsCount);

        return $map;
    }

    public function setRowsCount(int $count): self
    {
        $this->rowsCount = $count;

        return $this;
    }

    public function setColumnsCount(int $columnsCount): self
    {
        $this->columnsCount = $columnsCount;

        return $this;
    }

    public function setRoomsCount(int $roomsCount): self
    {
        $this->roomsCount = $roomsCount;

        return $this;
    }
}
