<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

use App\Interface\MapCellInterface;

class DummyMapCell implements MapCellInterface
{
    public static function getType(): string
    {
        return 'DUMMY';
    }

    public function getXCoordinate(): int
    {
        return 0;
    }

    public function getYCoordinate(): int
    {
        return 0;
    }
}
