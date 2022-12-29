<?php

namespace App\Service\Map\Railroad;

use App\Interface\MapCellInterface;
use App\Interface\TreasureInterface;

abstract class Cell implements MapCellInterface
{
    private int $x;

    private int $y;

    public function getXCoordinate(): int
    {
        return $this->x;
    }

    public function setXCoordinate(int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getYCoordinate(): int
    {
        return $this->y;
    }

    public function setYCoordinate(int $y): self
    {
        $this->y = $y;

        return $this;
    }

    abstract public static function getType(): string;

    abstract public function getTreasure(): ?TreasureInterface;

}
