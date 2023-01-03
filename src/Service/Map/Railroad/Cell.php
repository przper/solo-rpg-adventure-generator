<?php

namespace App\Service\Map\Railroad;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

abstract class Cell implements MapCellInterface
{
    private Coordinates $coordinates;

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(Coordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    abstract public function getType(): string;

    abstract public function getTreasure(): ?TreasureInterface;
}
