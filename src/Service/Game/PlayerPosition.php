<?php

namespace App\Service\Game;

use App\Interface\MapCellInterface;

class PlayerPosition
{
    public function __construct(
        private int $xCoordinate,
        private int $yCoordinate
    ) {
      //
    }

    public function getX(): int
    {
        return $this->xCoordinate;
    }

    public function getY(): int
    {
        return $this->yCoordinate;
    }

    public function move(int $x, int $y): self
    {
        $this->xCoordinate += $x;
        $this->yCoordinate += $y;

        return $this;
    }

    public static function fromCell(MapCellInterface $cell): self
    {
        $position = new self(
            $cell->getXCoordinate(),
            $cell->getYCoordinate()
        );

        return $position;
    }
}
