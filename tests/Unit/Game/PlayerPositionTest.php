<?php

namespace App\Tests\Unit\Game;

use App\Core\Helper\Coordinates;
use App\Game\Movement;
use App\Game\MovementDirection;
use App\Game\PlayerPosition;
use App\Tests\Unit\Game\Fixtures\DummyRoom;
use PHPUnit\Framework\TestCase;

class PlayerPositionTest extends TestCase
{
    /** @test */
    public function it_has_coordinates()
    {
        $position = new PlayerPosition(Coordinates::fromIntegers(0, 0));

        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $position->getCoordinates()
        );
    }

    /** @test */
    public function it_can_be_moved()
    {
        $position = new PlayerPosition(Coordinates::fromIntegers(10, 10));
        $position->movePlayer(Movement::new()->add(MovementDirection::East)->add(MovementDirection::South));

        $this->assertEquals(
            Coordinates::fromIntegers(11, 11),
            $position->getCoordinates(),
        );
    }
}
