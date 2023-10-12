<?php

namespace App\Tests\Unit\Game;

use App\Helper\Coordinates;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Game\Game;
use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;
use App\Tests\Unit\Game\Fixtures\DummyMap;

class GameTest extends TestCase
{
    public function test_it_have_status(): void
    {
        $game = new Game(new DummyMap(), new EncountersPlan([]));

        $this->assertSame('ready', $game->getStatus());
        $this->assertFalse($game->isRunning());

        $game->start();

        $this->assertSame('running', $game->getStatus());
        $this->assertTrue($game->isRunning());
    }

    /** @test */
    public function it_has_player_position()
    {
        $game = new Game(new DummyMap(), new EncountersPlan([]));

        $this->assertInstanceOf(PlayerPosition::class, $game->getPlayerPosition());
    }

    /** @test */
    public function player_position_can_be_moved()
    {
        $game = new Game(new DummyMap(), new EncountersPlan([]));

        $game->movePlayerByIntegers(1, 1);
        $this->assertEquals(
            Coordinates::fromIntegers(1, 1),
            $game->getPlayerPosition()->getCoordinates()
        );
    }

    /** @test */
    public function it_stores_visited_cells()
    {
        $game = new Game(new DummyMap(), new EncountersPlan([]));

        $this->assertCount(0, $game->getVisitedCells());

        $game->start();

        $this->assertCount(1, $game->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $game->getVisitedCells()[0]
        );

        $game->movePlayerByIntegers(1, 1);
        $this->assertCount(2, $game->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(1, 1),
            $game->getVisitedCells()[1]
        );

        $game->movePlayerByIntegers(-1, -1);
        $this->assertCount(2, $game->getVisitedCells());

        $game->movePlayerByIntegers(2, 2);
        $this->assertCount(3, $game->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(2, 2),
            $game->getVisitedCells()[2]
        );
    }
}
