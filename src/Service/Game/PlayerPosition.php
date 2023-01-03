<?php

namespace App\Service\Game;

use App\Helper\Coordinates;
use App\Interface\MapCellInterface;

class PlayerPosition
{
    public function __construct(
        private Coordinates $coordinates
    ) {
      //
    }

    public function getX(): int
    {
        return $this->coordinates->getX();
    }

    public function getY(): int
    {
        return $this->coordinates->getY();
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(Coordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function moveBy(int $deltaX, int $deltaY): self
    {
        $this->coordinates->moveBy($deltaX, $deltaY);

        return $this;
    }

    public static function fromCell(MapCellInterface $cell): self
    {
        $position = new self(clone $cell->getCoordinates());

        return $position;
    }
}
