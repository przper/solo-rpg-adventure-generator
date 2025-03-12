<?php

namespace App\Tests\Unit\MapRenderer\Fixtures;

use App\Interface\MapGeneratorInterface;
use App\Service\Map\Core\Map;

class DummyMapGenerator implements MapGeneratorInterface
{
    public function create(): Map
    {
        return new Map(10, 10);
    }
}
