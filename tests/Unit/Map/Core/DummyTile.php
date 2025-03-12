<?php

namespace App\Tests\Unit\Map\Core;

use App\Service\Map\Core\Tile;

final readonly class DummyTile extends Tile
{
    public function getType(): string
    {
        return 'room';
    }
}
