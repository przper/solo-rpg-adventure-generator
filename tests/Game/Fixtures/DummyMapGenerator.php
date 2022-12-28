<?php

namespace App\Tests\Game\Fixtures;

use App\Interface\MapGeneratorInterface;
use App\Interface\MapInterface;

class DummyMapGenerator implements MapGeneratorInterface
{
    public function create(): MapInterface
    {
        return new DummyMap();
    }
}