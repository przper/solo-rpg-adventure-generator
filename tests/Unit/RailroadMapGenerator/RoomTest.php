<?php

namespace App\Tests\Unit\RailroadMapGenerator;

use App\Helper\Coordinates;
use PHPUnit\Framework\TestCase;
use App\Service\Map\Railroad\Room;
use App\Service\Treasure\Treasure;
use App\Interface\TreasureInterface;
use App\Tests\Unit\RailroadMapGenerator\Fixtures\DummyTreasure;

class RoomTest extends TestCase
{
    /** @test */
    public function it_has_x_and_y_coordinates()
    {
        $room = Room::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertInstanceOf(Coordinates::class, $room->getCoordinates());
        $this->assertEquals(
            Coordinates::fromIntegers(0, 1),
            $room->getCoordinates()
        );
    }

    /** @test */
    public function it_has_type()
    {
        $room = Room::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertIsString($room->getType());
        $this->assertEquals(Room::TYPE, $room->getType());
    }

    /** @test */
    public function it_has_treasure()
    {
        $room = Room::fromCoordinates(Coordinates::fromIntegers(0, 1));

        $this->assertNull($room->getTreasure());

        $room->setTreasure(new DummyTreasure());

        $this->assertInstanceOf(TreasureInterface::class, $room->getTreasure());
    }
}
