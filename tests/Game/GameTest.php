<?php

namespace App\Tests\Game;

use App\Service\Game\Game;
use App\Interface\MapInterface;
use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;
use App\Tests\Game\Fixtures\DummyMap;

class GameTest extends TestCase
{
    /** @test */
    public function map_can_be_set()
    {
        $game = new Game();

        $map = new DummyMap();
        $game->setMap($map);

        $this->assertInstanceOf(MapInterface::class, $game->getMap());
    }

    /** @test */
    public function player_position_can_be_set()
    {
        $game = new Game();

        $position = new PlayerPosition();
        $game->setPosition($position);

        $this->assertInstanceOf(PlayerPosition::class, $game->getPosition());
    }
}
