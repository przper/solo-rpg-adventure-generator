<?php

namespace App\Game;

use App\Core\Helper\Coordinates;
use App\EncountersPlanning\Encounter;

interface EncountersInterface
{
    public function getEncounter(Coordinates $coordinates): ?Encounter;

    public function resolve(Coordinates $coordinates, string $result): void;
}
