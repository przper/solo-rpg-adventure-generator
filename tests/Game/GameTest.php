<?php

namespace App\Tests\Game;

use App\Service\Game\Game;
use App\Interface\MapInterface;
use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;
use App\Tests\Game\Fixtures\DummyMap;
use App\Tests\Game\Fixtures\DummyMapRenderer;

class GameTest extends TestCase
{
    /** @test */
    public function it_has_map_instance()
    {
        $game = new Game();

        $map = new DummyMap();
        $game->setMap($map);

        $this->assertInstanceOf(MapInterface::class, $game->getMap());
    }

    /** @test */
    public function it_stores_player_position()
    {
        $game = new Game();

        $position = new PlayerPosition(0, 0);
        $game->setPosition($position);

        $this->assertInstanceOf(PlayerPosition::class, $game->getPosition());
    }
}
