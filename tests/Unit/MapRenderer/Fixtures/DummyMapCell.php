<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;

class DummyMapCell implements MapCellInterface
{
    public function __construct(
        private string $type = 'DUMMY'
    ) {
        //
    }

    public function getType(): string
    {
        return $this->type;
    }
    
    public function getCoordinates(): Coordinates
    {
        return Coordinates::fromIntegers(0, 0);
    }
}
