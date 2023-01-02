<?php

namespace App\Service\Game;

use App\Interface\MapGeneratorInterface;

class GameFactory
{
    private MapGeneratorInterface $mapBuilder;

    public function getMapBuilder(): MapGeneratorInterface
    {
        return $this->mapBuilder;
    }

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

        $startCell = $map->getRooms()[0] ?? $map->getCells()[0][0];
        $game->setPlayerPosition(PlayerPosition::fromCell($startCell));

        return $game;
    }
}
