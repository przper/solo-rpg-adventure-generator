<?php

namespace App\Service\Game;

use App\Interface\MapGeneratorInterface;

class GameFactory
{
    private MapGeneratorInterface $mapBuilder;

    public function setMapBuilder(MapGeneratorInterface $mapBuilder): self
    {
        $this->mapBuilder = $mapBuilder;

        return $this;
    }

    public function create(): Game
    {
        $game = new Game();

        $map = $this->mapBuilder->create();
        $game->setMap($map);

        $game->setPosition(PlayerPosition::fromRoom($map->getRooms()[0]));

        return $game;
    }
}
