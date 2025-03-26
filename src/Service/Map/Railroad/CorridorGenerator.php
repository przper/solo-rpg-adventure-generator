<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Service\Map\Core\Corridor;

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
