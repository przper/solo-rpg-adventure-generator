<?php

namespace App\Tests\Unit\Game;

use App\Core\Helper\Coordinates;
use App\Core\Map\Map;
use App\Core\Map\Room;
use App\EncountersPlanning\EncountersPlan;
use App\Game\Game;
use App\Game\Movement;
use App\Game\MovementDirection;
use App\Game\PlayerPosition;
use App\Tests\Fixtures\Dummies\DummyEncounters;
use App\Tests\Fixtures\Dummies\DummyFogOfWar;
use PHPUnit\Framework\TestCase;

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
            elements: [
                Room::create([
                    Coordinates::fromIntegers(0, 0),
                    Coordinates::fromIntegers(1, 0),
                    Coordinates::fromIntegers(2, 0),
                    Coordinates::fromIntegers(0, 1),
                    Coordinates::fromIntegers(1, 1),
                    Coordinates::fromIntegers(2, 1),
                    Coordinates::fromIntegers(0, 2),
                    Coordinates::fromIntegers(1, 2),
                    Coordinates::fromIntegers(2, 2),
                ]),
            ],
        );

        $this->sut = new Game($map, new DummyFogOfWar(), new DummyEncounters());
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
