<?php

namespace App\Game;

use App\Core\Encounter\Encounter;
use App\Core\Helper\Coordinates;

interface EncountersInterface
{
    public function getEncounter(Coordinates $coordinates): ?Encounter;

    public function resolve(Coordinates $coordinates, string $result): void;
}
