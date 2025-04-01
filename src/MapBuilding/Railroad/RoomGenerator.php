<?php

namespace App\MapBuilding\Railroad;

use App\Core\Helper\Coordinates;
use App\Core\Map\Room;

class RoomGenerator
{
    public function generate(Coordinates $coordinates): Room
    {
        return Room::create([$coordinates]);
    }

    public function starter(): Room
    {
        return Room::create([Coordinates::fromIntegers(0, 0)]);
    }
}
