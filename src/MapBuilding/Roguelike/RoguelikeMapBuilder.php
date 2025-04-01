<?php

namespace App\MapBuilding\Roguelike;

use App\Core\Map\Map;
use App\MapBuilding\MapGeneratorInterface;

class RoguelikeMapBuilder implements MapGeneratorInterface
{
    private int $rowsCount;
    private int $columnsCount;
    private int $roomsCount;

    public function create(): Map
    {
        return new Map($this->rowsCount, $this->columnsCount);
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
