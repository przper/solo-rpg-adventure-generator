<?php

namespace App\Tests\Fixtures\Dummies;

use App\Core\Encounter\Encounter;
use App\Core\Helper\Coordinates;
use App\Game\EncountersInterface;

class DummyEncounters implements EncountersInterface
{

    public function getEncounter(Coordinates $coordinates): ?Encounter
    {
        return null;
    }

    public function resolve(Coordinates $coordinates, string $result): void
    {
        //
    }
}
