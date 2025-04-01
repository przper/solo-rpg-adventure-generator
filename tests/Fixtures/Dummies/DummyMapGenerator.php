<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Map\Map;
use App\MapBuilding\MapGeneratorInterface;

class DummyMapGenerator implements MapGeneratorInterface
{
    public function create(): Map
    {
        return new Map(10, 10);
    }
}
