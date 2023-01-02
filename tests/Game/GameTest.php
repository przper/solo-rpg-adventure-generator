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
    public function it_has_map_instance()
    {
        $game = new Game();

        $game->setMap(new DummyMap());

        $this->assertInstanceOf(MapInterface::class, $game->getMap());
    }

    /** @test */
    public function it_has_player_position()
    {
        $game = new Game();

        $game->setPlayerPosition(new PlayerPosition(0, 0));

        $this->assertInstanceOf(PlayerPosition::class, $game->getPlayerPosition());
    }

    /** @test */
    public function player_position_can_be_moved()
    {
        $game = new Game();
        $game->setPlayerPosition(new PlayerPosition(0, 0));

        $game->movePlayer(1, 1);
        $this->assertEquals(1, $game->getPlayerPosition()->getX());
        $this->assertEquals(1, $game->getPlayerPosition()->getY());
    }

    /** @test */
    public function it_stores_visited_cells()
    {
        $game = new Game();
        $game->setPlayerPosition(new PlayerPosition(0, 0));

        $this->assertCount(1, $game->getVisitedCells());
        $this->assertEquals(['x'=>0,'y'=>0], $game->getVisitedCells()[0]);

        $game->movePlayer(1, 1);
        $this->assertCount(2, $game->getVisitedCells());
        $this->assertEquals(['x'=>1,'y'=>1], $game->getVisitedCells()[1]);
    }
}
