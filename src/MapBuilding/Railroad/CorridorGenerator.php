<?php

namespace App\MapBuilding\Railroad;

use App\Core\Helper\Coordinates;
use App\Core\Map\Corridor;

class CorridorGenerator
{
    public function generate(Coordinates $start, int $length): Corridor
    {
        $coordinates = [$start];

        for ($i = 1; count($coordinates) < $length; $i++) {
            $coordinates[] = Coordinates::fromIntegers($start->x+$i, $start->y);
        }

        return Corridor::create($coordinates);
    }
}
