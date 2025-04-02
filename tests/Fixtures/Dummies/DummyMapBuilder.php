<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Map\Map;
use App\MapBuilding\MapBuilderInterface;

class DummyMapBuilder implements MapBuilderInterface
{
    public function build(): Map
    {
        return new Map(10, 10);
    }
}
