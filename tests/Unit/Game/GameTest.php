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

    public function test_it_checks_available_moves(): void
    {
        $this->assertCount(2, $this->sut->getAvailableActions()->movement);
        $this->assertContains('south', $this->sut->getAvailableActions()->movement);
        $this->assertContains('east', $this->sut->getAvailableActions()->movement);

        $this->sut->movePlayer(Movement::new()->add(MovementDirection::East, 2)->add(MovementDirection::South, 2));

        $this->assertCount(2, $this->sut->getAvailableActions()->movement);
        $this->assertContains('north', $this->sut->getAvailableActions()->movement);
        $this->assertContains('west', $this->sut->getAvailableActions()->movement);
    }
}
