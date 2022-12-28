<?php

namespace App\Service\Map\Railroad;

use App\Interface\TreasureGeneratorInterface;

class RoomGenerator
{
    private int $treasurePercentageChangeInInt = 50;

    public function __construct(
        private TreasureGeneratorInterface $treasureGenerator
    ) {
        //
    }

    public function setTreasurePercentageChangeInInt(int $treasurePercentageChangeInInt): self
    {
        $this->treasurePercentageChangeInInt = $treasurePercentageChangeInInt;

        return $this;
    }

    public function generate(): Room
    {
        $room = new Room();

        if (rand(0, 100) < $this->treasurePercentageChangeInInt) {
            $treasure = $this->treasureGenerator->generate();
            $room->setTreasure($treasure);
        }

        return $room;
    }

}