<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Service\Map\Core\Room;

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
