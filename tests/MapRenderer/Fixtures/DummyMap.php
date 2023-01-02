<?php

namespace App\Tests\MapRenderer\Fixtures;

use App\Interface\MapInterface;

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
}
