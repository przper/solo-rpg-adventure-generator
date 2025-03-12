<?php

namespace App\Service\Map\Roguelike;

use App\Interface\MapGeneratorInterface;
use App\Service\Map\Core\Map;

class RoguelikeMapBuilder implements MapGeneratorInterface
{
    private int $rowsCount;
    private int $columnsCount;

    public function create(): Map
    {
        $map = new Map($this->rowsCount, $this->columnsCount);

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
