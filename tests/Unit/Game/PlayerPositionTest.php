<?php

namespace App\Tests\Unit\Game;

use App\Helper\Coordinates;
use App\Tests\Unit\Map\Core\Fixtures\DummyRoom;
use PHPUnit\Framework\TestCase;
use App\Service\Game\PlayerPosition;

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
        $position->moveBy(1, 1);

        $this->assertEquals(
            Coordinates::fromIntegers(11, 11),
            $position->getCoordinates()
        );
    }

    /** @test */
    public function it_can_be_created_from_MapCell()
    {
        $position = PlayerPosition::fromCell(new DummyRoom(Coordinates::fromIntegers(0, 0)));

        $this->assertEquals(
            Coordinates::fromIntegers(0, 0),
            $position->getCoordinates()
        );
    }
}
