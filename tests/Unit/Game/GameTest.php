<?php

namespace App\Tests\Unit\Game;

use App\Enum\MovementDirection;
use App\Helper\Coordinates;
use App\Service\EncountersPlanner\EncountersPlan;
use App\Service\Game\Game;
use App\Service\Game\Movement;
use App\Service\Map\Core\Map;
use App\Tests\Unit\Game\Fixtures\DummyRoom;
use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;

class GameTest extends TestCase
{
    private Game $sut;

    protected function setUp(): void
    {
        /**
         * Plain text example:
         *
         * R R R #
         * R R R #
         * R R R #
         * # # # #
         */
        $map = new Map(
            width: 5,
            height: 5,
            tiles: [
                new DummyRoom(Coordinates::fromIntegers(0, 0)),
                new DummyRoom(Coordinates::fromIntegers(1, 0)),
                new DummyRoom(Coordinates::fromIntegers(2, 0)),
                new DummyRoom(Coordinates::fromIntegers(0, 1)),
                new DummyRoom(Coordinates::fromIntegers(1, 1)),
                new DummyRoom(Coordinates::fromIntegers(2, 1)),
                new DummyRoom(Coordinates::fromIntegers(0, 2)),
                new DummyRoom(Coordinates::fromIntegers(1, 2)),
                new DummyRoom(Coordinates::fromIntegers(2, 2)),
            ],
        );

        $this->sut = new Game($map, new EncountersPlan());
    }

    public function test_it_have_status(): void
    {
        $this->assertSame('ready', $this->sut->getStatus());
        $this->assertFalse($this->sut->isRunning());

        $this->sut->start();

        $this->assertSame('running', $this->sut->getStatus());
        $this->assertTrue($this->sut->isRunning());
    }

    /** @test */
    public function it_has_player_position()
    {
        $this->assertInstanceOf(PlayerPosition::class, $this->sut->getPlayerPosition());
    }

    /** @test */
    public function player_position_can_be_moved()
    {
        $this->sut->movePlayer(Movement::new()->add(MovementDirection::East)->add(MovementDirection::South));

        $this->assertEquals(
            Coordinates::fromIntegers(1, 1),
            $this->sut->getPlayerPosition()->getCoordinates()
        );
    }

    /** @test */
    public function it_stores_visited_cells()
    {
        $this->assertCount(0, $this->sut->getVisitedCells());

        $this->sut->start();

        $this->assertCount(1, $this->sut->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $this->sut->getVisitedCells()[0]
        );

        $this->sut->movePlayer(Movement::new()->add(MovementDirection::East)->add(MovementDirection::South));
        $this->assertCount(2, $this->sut->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(1, 1),
            $this->sut->getVisitedCells()[1]
        );

        $this->sut->movePlayer(Movement::new()->add(MovementDirection::West)->add(MovementDirection::North));
        $this->assertCount(2, $this->sut->getVisitedCells());

        $this->sut->movePlayer(Movement::new()->add(MovementDirection::East, 2)->add(MovementDirection::South, 2));
        $this->assertCount(3, $this->sut->getVisitedCells());
        $this->assertEquals(
            Coordinates::fromIntegers(2, 2),
            $this->sut->getVisitedCells()[2]
        );
    }

    public function test_it_checks_available_moves(): void
    {
        $this->assertCount(2, $this->sut->actions->movement);
        $this->assertContains('south', $this->sut->actions->movement);
        $this->assertContains('east', $this->sut->actions->movement);

        $this->sut->movePlayer(Movement::new()->add(MovementDirection::East, 2)->add(MovementDirection::South, 2));

        $this->assertCount(2, $this->sut->actions->movement);
        $this->assertContains('north', $this->sut->actions->movement);
        $this->assertContains('west', $this->sut->actions->movement);
    }
}
