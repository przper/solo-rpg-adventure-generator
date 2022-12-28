<?php

namespace App\Service\Map\Railroad;

use App\Interface\MapInterface;

class Map implements MapInterface
{
    /** @var Cell[] $cells */
    private array $cells = [];

    public function getCells(): array
    {
        return $this->cells;
    }

    public function getRooms(): array
    {
        return array_filter(
            $this->cells,
            fn (Cell $c) => $c->getType() === Room::TYPE
        );
    }

    public function getCorridors(): array
    {
        return array_filter(
            $this->cells,
            fn (Cell $c) => $c->getType() === Corridor::TYPE
        );
    }

    public function addCell(Cell $cell): self
    {
        if (! in_array($cell, $this->cells)) {
            $cell->setX($this->getLength());
            $this->cells[] = $cell;
        }

        return $this;
    }

    public function getLength(): int
    {
        return count($this->cells);
    }
}
