<?php

namespace App\Service\Game;

use App\Interface\MapInterface;
use App\Service\Game\Exception\NoGeneratedMapException;
use App\Service\Game\Exception\UnknownPlayerPositionException;

class Game
{
    private MapInterface $map;

    private PlayerPosition $playerPosition;

    public function getMap(): MapInterface
    {
        return $this->map;
    }

    public function setMap(MapInterface $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getPlayerPosition(): PlayerPosition
    {
        return $this->playerPosition;
    }

    public function setPlayerPosition(PlayerPosition $position): self
    {
        $this->playerPosition = $position;

        return $this;
    }

    public function movePlayer(int $x, int $y): self
    {
        $this->playerPosition->move($x, $y);

        return $this;
    }

    public function start(): void
    {
        if (! $this->map) {
            throw new NoGeneratedMapException();
        }

        if (! $this->playerPosition) {
            throw new UnknownPlayerPositionException();
        }

        //
    }
}
