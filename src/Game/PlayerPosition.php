<?php

namespace App\Game;

use App\Core\Helper\Coordinates;

class PlayerPosition
{
    public function __construct(
        private Coordinates $coordinates
    ) {
    }

    public function getX(): int
    {
        return $this->coordinates->x;
    }

    public function getY(): int
    {
        return $this->coordinates->y;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function movePlayer(Movement $move): self
    {
        $this->coordinates = $this->coordinates->moveBy($move->deltaX, $move->deltaY);

        return $this;
    }
}
