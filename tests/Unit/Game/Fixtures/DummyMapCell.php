<?php

namespace App\Tests\Unit\Game\Fixtures;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;

class DummyMapCell implements MapCellInterface
{
    public function getType(): string
    {
        return 'DUMMY';
    }

    public function getCoordinates(): Coordinates
    {
        return Coordinates::fromIntegers(0, 0);
    }
}
