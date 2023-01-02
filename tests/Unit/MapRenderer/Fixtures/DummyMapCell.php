<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

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

    public function getXCoordinate(): int
    {
        return 0;
    }

    public function getYCoordinate(): int
    {
        return 0;
    }
}
