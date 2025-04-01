<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Helper\Coordinates;
use App\Game\FogOfWarInterface;

class DummyFogOfWar implements FogOfWarInterface
{
    public function visit(Coordinates $coordinates): void
    {
        //
    }

    public function getVisitedCoordinates(): array
    {
        return [];
    }

    public function getKnownCoordinates(): array
    {
        return [];
    }

    public function isVisited(Coordinates $coordinates): bool
    {
        return false;
    }

    public function isKnown(Coordinates $coordinates): bool
    {
        return false;
    }
}
