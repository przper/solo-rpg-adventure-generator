<?php

namespace App\Service\Game;

use App\Helper\Coordinates;
use App\Service\Map\Core\Tile;

class PlayerPosition
{
    public function __construct(
        private Coordinates $coordinates
    ) {
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

    public function movePlayer(Movement $move): self
    {
        $this->coordinates = $this->coordinates->moveBy($move->deltaX, $move->deltaY);

        return $this;
    }

    public static function fromTile(Tile $tile): self
    {
        return new self(clone $tile->getCoordinates());
    }
}
