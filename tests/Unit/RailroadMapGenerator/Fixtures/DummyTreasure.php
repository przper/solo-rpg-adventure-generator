<?php

namespace App\Tests\Unit\RailroadMapGenerator\Fixtures;

use App\Interface\TreasureInterface;

class DummyTreasure implements TreasureInterface
{
    public function jsonSerialize(): mixed
    {
        return [];
    }
}
