<?php

namespace App\Service\Game;

use App\Interface\MapInterface;
use App\Service\Game\Exception\NoGeneratedMapException;
use App\Service\Game\Exception\UnknownPlayerPositionException;

class Game
{
    private MapInterface $map;

    private PlayerPosition $position;

    public function getMap(): MapInterface
    {
        return $this->map;
    }

    public function setMap(MapInterface $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getPosition(): PlayerPosition
    {
        return $this->position;
    }

    public function setPosition(PlayerPosition $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function start(): void
    {
        if (! $this->map) {
            throw new NoGeneratedMapException();
        }

        if (! $this->position) {
            throw new UnknownPlayerPositionException();
        }

        //
    }
}
