<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

use App\Helper\Coordinates;
use App\Interface\MapInterface;
use App\Interface\MapCellInterface;
use App\Tests\Unit\MapRenderer\Fixtures\DummyMapCell;

class DummyMap implements MapInterface
{
    public function getRooms(): array
    {
        return [];
    }

    public function getCells(): array
    {
        return [
            [ new DummyMapCell() ]
        ];
    }

    public function getCell(Coordinates $coordinates): ?MapCellInterface
    {
        return $this->getCells()[$coordinates->getX()][$coordinates->getY()] ?? null;
    }
}
